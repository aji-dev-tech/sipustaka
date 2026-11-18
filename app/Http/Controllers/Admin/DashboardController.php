<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalJudul = Buku::count();
        $dipinjam = Peminjaman::whereIn('status', ['approved', 'dipinjam'])->count();
        $pending = Peminjaman::where('status', 'pending')->count();
        $telat = Peminjaman::whereIn('status', ['approved', 'dipinjam'])
            ->whereDate('tgl_kembali_rencana', '<', now())
            ->count();
        $totalAnggota = User::where('role', 'siswa')->count();

        $aktivitasTerbaru = Peminjaman::with(['user', 'buku'])->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalJudul', 'dipinjam', 'pending', 'telat', 'totalAnggota', 'aktivitasTerbaru'
        ));
    }
}