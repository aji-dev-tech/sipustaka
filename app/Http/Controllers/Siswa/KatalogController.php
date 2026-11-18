<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;

class KatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = Buku::with('kategori');

        if ($request->filled('cari')) {
            $query->where(function ($q) use ($request) {
                $q->where('judul', 'like', '%' . $request->cari . '%')
                  ->orWhere('penulis', 'like', '%' . $request->cari . '%');
            });
        }

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        $bukus = $query->latest()->paginate(8)->withQueryString();
        $kategoris = Kategori::orderBy('nama_kategori')->get();

        return view('siswa.katalog.index', compact('bukus', 'kategoris'));
    }
}