@extends('adminlte::page')

@section('title', 'Master Kategori - SIPUSTAKA')

@section('content_header')
    <h1>Master Kategori Buku</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <a href="{{ route('admin.kategori.create') }}" class="btn btn-primary btn-sm">+ Tambah Kategori</a>
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
                        <th>Nama Kategori</th>
                        <th style="width: 160px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($kategoris as $i => $kategori)
                        <tr>
                            <td>{{ $kategoris->firstItem() + $i }}</td>
                            <td>{{ $kategori->nama_kategori }}</td>
                            <td>
                                <a href="{{ route('admin.kategori.edit', $kategori->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('admin.kategori.destroy', $kategori->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus kategori ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center">Belum ada data kategori.</td></tr>
                    @endforelse
                </tbody>
            </table>
            {{ $kategoris->links() }}
        </div>
    </div>
@endsection