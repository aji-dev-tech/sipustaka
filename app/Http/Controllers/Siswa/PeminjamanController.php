<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    public function index()
    {
        $peminjamen = Peminjaman::with(['buku', 'denda'])
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('siswa.peminjaman.index', compact('peminjamen'));
    }

    public function create(Buku $buku)
    {
        if ($buku->stok < 1) {
            return redirect()->route('siswa.katalog.index')->with('error', 'Stok buku ini sedang habis.');
        }

        return view('siswa.peminjaman.create', compact('buku'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'buku_id' => 'required|exists:bukus,id',
            'tanggal_pinjam' => 'required|date|after_or_equal:today',
            'tgl_kembali_rencana' => 'required|date|after:tanggal_pinjam',
        ]);

        $buku = Buku::findOrFail($validated['buku_id']);

        if ($buku->stok < 1) {
            return back()->with('error', 'Stok buku ini sedang habis.');
        }

        // Cegah pengajuan ganda: satu siswa tidak boleh punya dua pengajuan/peminjaman
        // aktif untuk buku yang sama (kalau tidak, bisa "memborong" stok lewat klik berulang).
        $sudahAda = Peminjaman::where('user_id', auth()->id())
            ->where('buku_id', $buku->id)
            ->whereIn('status', ['pending', 'approved', 'dipinjam'])
            ->exists();

        if ($sudahAda) {
            return back()->withInput()->with('error', 'Kamu masih punya pengajuan atau peminjaman aktif untuk buku ini.');
        }

        Peminjaman::create([
            'user_id' => auth()->id(),
            'buku_id' => $buku->id,
            'tanggal_pinjam' => $validated['tanggal_pinjam'],
            'tgl_kembali_rencana' => $validated['tgl_kembali_rencana'],
            'status' => 'pending',
        ]);

        return redirect()->route('siswa.peminjaman.index')->with('success', 'Pengajuan peminjaman berhasil dikirim, menunggu verifikasi admin.');
    }
}
