<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $sedangDipinjam = Peminjaman::where('user_id', $userId)
            ->whereIn('status', ['approved', 'dipinjam'])
            ->count();

        $menungguVerifikasi = Peminjaman::where('user_id', $userId)
            ->where('status', 'pending')
            ->count();

        $totalDenda = Peminjaman::where('user_id', $userId)
            ->whereHas('denda', fn ($q) => $q->where('status_bayar', 'belum_bayar'))
            ->with('denda')
            ->get()
            ->sum(fn ($p) => $p->denda->nominal_denda ?? 0);

        return view('siswa.dashboard', compact('sedangDipinjam', 'menungguVerifikasi', 'totalDenda'));
    }
}
