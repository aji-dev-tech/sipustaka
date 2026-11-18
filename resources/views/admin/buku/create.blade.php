@extends('adminlte::page')

@section('title', 'Tambah Buku - SIPUSTAKA')

@section('content_header')
    <h1>Tambah Buku</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.buku.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group mb-3">
                    <label>Judul Buku</label>
                    <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror" value="{{ old('judul') }}">
                    @error('judul') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="form-group mb-3">
                    <label>Penulis</label>
                    <input type="text" name="penulis" class="form-control @error('penulis') is-invalid @enderror" value="{{ old('penulis') }}">
                    @error('penulis') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="form-group mb-3">
                    <label>Penerbit</label>
                    <input type="text" name="penerbit" class="form-control" value="{{ old('penerbit') }}">
                </div>
                <div class="form-group mb-3">
                    <label>Tahun Terbit</label>
                    <input type="number" name="tahun_terbit" class="form-control" value="{{ old('tahun_terbit') }}">
                </div>
                <div class="form-group mb-3">
                    <label>ISBN</label>
                    <input type="text" name="isbn" class="form-control" value="{{ old('isbn') }}">
                </div>
                <div class="form-group mb-3">
                    <label>Kategori</label>
                    <select name="kategori_id" class="form-control @error('kategori_id') is-invalid @enderror">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($kategoris as $kategori)
                            <option value="{{ $kategori->id }}" {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                {{ $kategori->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                    @error('kategori_id') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="form-group mb-3">
                    <label>Stok</label>
                    <input type="number" name="stok" class="form-control @error('stok') is-invalid @enderror" value="{{ old('stok', 0) }}">
                    @error('stok') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="form-group mb-3">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi') }}</textarea>
                </div>
                <div class="form-group mb-3">
                    <label>Cover Buku</label>
                    <input type="file" name="cover" class="form-control @error('cover') is-invalid @enderror">
                    @error('cover') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('admin.buku.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
@endsection