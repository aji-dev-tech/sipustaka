@extends('adminlte::page')

@section('title', 'Katalog Buku - SIPUSTAKA')

@section('content_header')
    <h1>Katalog Buku</h1>
@endsection

@section('content')
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <form method="GET" class="row g-2 mb-3">
                <div class="col-md-6">
                    <input type="text" name="cari" class="form-control" placeholder="Cari judul / penulis..." value="{{ request('cari') }}">
                </div>
                <div class="col-md-4">
                    <select name="kategori_id" class="form-control">
                        <option value="">-- Semua Kategori --</option>
                        @foreach ($kategoris as $kategori)
                            <option value="{{ $kategori->id }}" {{ request('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                {{ $kategori->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Cari</button>
                </div>
            </form>

            <div class="row">
                @forelse ($bukus as $buku)
                    <div class="col-md-3 mb-4">
                        <div class="card h-100">
                            <div class="text-center pt-3">
                                @if ($buku->cover)
                                    <img src="{{ asset('storage/' . $buku->cover) }}" style="height: 140px; object-fit: cover;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 140px;">
                                        <span class="text-muted">No Cover</span>
                                    </div>
                                @endif
                            </div>
                            <div class="card-body">
                                <h6>{{ $buku->judul }}</h6>
                                <p class="text-muted small mb-1">{{ $buku->penulis }}</p>
                                <p class="small mb-2">
                                    <span class="badge bg-secondary">{{ $buku->kategori->nama_kategori ?? '-' }}</span>
                                    <span class="badge {{ $buku->stok > 0 ? 'bg-success' : 'bg-danger' }}">Stok: {{ $buku->stok }}</span>
                                </p>
                                @if ($buku->stok > 0)
                                    <a href="{{ route('siswa.peminjaman.create', $buku->id) }}" class="btn btn-primary btn-sm w-100">Ajukan Pinjam</a>
                                @else
                                    <button class="btn btn-secondary btn-sm w-100" disabled>Stok Habis</button>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-center">Buku tidak ditemukan.</p>
                @endforelse
            </div>
            {{ $bukus->links() }}
        </div>
    </div>
@endsection