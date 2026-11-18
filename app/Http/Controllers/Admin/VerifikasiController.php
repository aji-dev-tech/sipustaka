<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Denda;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VerifikasiController extends Controller
{
    private const DENDA_PER_HARI = 1000;

    public function index(Request $request)
    {
        $query = Peminjaman::with(['user', 'buku', 'denda']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $peminjamen = $query->latest()->paginate(10)->withQueryString();

        return view('admin.verifikasi.index', compact('peminjamen'));
    }

    public function approve(Peminjaman $peminjaman)
    {
        // Seluruh proses dalam satu transaksi + kunci baris (lockForUpdate), supaya:
        // - dua admin yang klik bersamaan tidak bisa menyetujui pengajuan yang sama dua kali,
        // - stok tidak bisa turun di bawah nol karena cek stok dan pengurangannya terpisah.
        [$tipe, $pesan] = DB::transaction(function () use ($peminjaman) {
            $p = Peminjaman::whereKey($peminjaman->id)->lockForUpdate()->firstOrFail();

            if ($p->status !== 'pending') {
                return ['error', 'Pengajuan ini sudah diproses sebelumnya.'];
            }

            $buku = Buku::whereKey($p->buku_id)->lockForUpdate()->firstOrFail();

            if ($buku->stok < 1) {
                return ['error', 'Stok buku sedang habis, tidak bisa disetujui.'];
            }

            $p->update([
                'status' => 'approved',
                'catatan_admin' => 'Disetujui oleh admin.',
            ]);
            $buku->decrement('stok');

            return ['success', 'Pengajuan berhasil disetujui.'];
        });

        return back()->with($tipe, $pesan);
    }

    public function reject(Request $request, Peminjaman $peminjaman)
    {
        $request->validate([
            'catatan_admin' => 'required|string|max:255',
        ]);

        // UPDATE bersyarat: hanya berhasil kalau status masih 'pending' pada saat query jalan.
        $diubah = Peminjaman::whereKey($peminjaman->id)
            ->where('status', 'pending')
            ->update([
                'status' => 'rejected',
                'catatan_admin' => $request->catatan_admin,
            ]);

        if (! $diubah) {
            return back()->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        return back()->with('success', 'Pengajuan berhasil ditolak.');
    }

    public function kembalikan(Peminjaman $peminjaman)
    {
        // Status, stok, dan denda harus berubah bersama-sama atau tidak sama sekali.
        [$tipe, $pesan] = DB::transaction(function () use ($peminjaman) {
            $p = Peminjaman::whereKey($peminjaman->id)->lockForUpdate()->firstOrFail();

            if (! in_array($p->status, ['approved', 'dipinjam'])) {
                return ['error', 'Peminjaman ini tidak bisa ditandai kembali.'];
            }

            $buku = Buku::whereKey($p->buku_id)->lockForUpdate()->first();

            if (! $buku) {
                return ['error', 'Data buku tidak ditemukan.'];
            }

            $tglRencana = Carbon::parse($p->tgl_kembali_rencana)->startOfDay();
            $tglAktual = now()->startOfDay();

            $p->update([
                'status' => 'dikembalikan',
                'tgl_kembali_aktual' => $tglAktual->toDateString(),
            ]);

            $buku->increment('stok');

            $hariTelat = $tglAktual->gt($tglRencana) ? (int) round($tglRencana->diffInDays($tglAktual)) : 0;

            if ($hariTelat > 0) {
                $nominalDenda = $hariTelat * self::DENDA_PER_HARI;

                // updateOrCreate + kolom unique(peminjaman_id): tidak mungkin ada denda ganda.
                Denda::updateOrCreate(
                    ['peminjaman_id' => $p->id],
                    [
                        'jumlah_hari_telat' => $hariTelat,
                        'nominal_denda' => $nominalDenda,
                        'status_bayar' => 'belum_bayar',
                    ]
                );

                return ['success', "Buku dikembalikan. Terlambat {$hariTelat} hari, denda Rp " . number_format($nominalDenda, 0, ',', '.')];
            }

            return ['success', 'Buku berhasil dikembalikan tepat waktu, tidak ada denda.'];
        });

        return back()->with($tipe, $pesan);
    }

    public function lunasi(Denda $denda)
    {
        $diubah = Denda::whereKey($denda->id)
            ->where('status_bayar', 'belum_bayar')
            ->update(['status_bayar' => 'lunas']);

        if (! $diubah) {
            return back()->with('error', 'Denda ini sudah ditandai lunas sebelumnya.');
        }

        return back()->with('success', 'Denda berhasil ditandai lunas.');
    }
}
