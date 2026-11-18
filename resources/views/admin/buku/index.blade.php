@extends('adminlte::page')

@section('title', 'Master Buku - SIPUSTAKA')

@section('content_header')
    <h1>Master Buku</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <a href="{{ route('admin.buku.create') }}" class="btn btn-primary btn-sm">+ Tambah Buku</a>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th style="width: 60px;">No</th>
                        <th style="width: 70px;">Cover</th>
                        <th>Judul</th>
                        <th>Penulis</th>
                        <th>Kategori</th>
                        <th>Stok</th>
                        <th style="width: 160px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($bukus as $i => $buku)
                        <tr>
                            <td>{{ $bukus->firstItem() + $i }}</td>
                            <td>
                                @if ($buku->cover)
                                    <img src="{{ asset('storage/' . $buku->cover) }}" width="40">
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $buku->judul }}</td>
                            <td>{{ $buku->penulis }}</td>
                            <td>{{ $buku->kategori->nama_kategori ?? '-' }}</td>
                            <td>{{ $buku->stok }}</td>
                            <td>
                                <a href="{{ route('admin.buku.edit', $buku->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('admin.buku.destroy', $buku->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus buku ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center">Belum ada data buku.</td></tr>
                    @endforelse
                </tbody>
            </table>
            {{ $bukus->links() }}
        </div>
    </div>
@endsection