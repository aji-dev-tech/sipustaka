<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $query = Peminjaman::with(['user', 'buku']);

        if ($request->filled('dari')) {
            $query->whereDate('tanggal_pinjam', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('tanggal_pinjam', '<=', $request->sampai);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $peminjamen = $query->latest()->paginate(15)->withQueryString();

        return view('admin.laporan.index', compact('peminjamen'));
    }

    public function cetakPdf(Request $request)
    {
        $query = Peminjaman::with(['user', 'buku']);

        if ($request->filled('dari')) {
            $query->whereDate('tanggal_pinjam', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('tanggal_pinjam', '<=', $request->sampai);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $peminjamen = $query->latest()->get();

        $pdf = Pdf::loadView('admin.laporan.pdf', compact('peminjamen'));

        return $pdf->download('laporan-peminjaman-' . now()->format('Y-m-d') . '.pdf');
    }
}