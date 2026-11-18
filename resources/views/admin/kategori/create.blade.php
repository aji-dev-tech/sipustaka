@extends('adminlte::page')

@section('title', 'Tambah Kategori - SIPUSTAKA')

@section('content_header')
    <h1>Tambah Kategori</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.kategori.store') }}" method="POST">
                @csrf
                <div class="form-group mb-3">
                    <label>Nama Kategori</label>
                    <input type="text" name="nama_kategori" class="form-control @error('nama_kategori') is-invalid @enderror" value="{{ old('nama_kategori') }}">
                    @error('nama_kategori')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('admin.kategori.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
@endsection