@extends('adminlte::page')

@section('title', 'Edit Buku - SIPUSTAKA')

@section('content_header')
    <h1>Edit Buku</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.buku.update', $buku->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group mb-3">
                    <label>Judul Buku</label>
                    <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror" value="{{ old('judul', $buku->judul) }}">
                    @error('judul') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="form-group mb-3">
                    <label>Penulis</label>
                    <input type="text" name="penulis" class="form-control @error('penulis') is-invalid @enderror" value="{{ old('penulis', $buku->penulis) }}">
                    @error('penulis') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="form-group mb-3">
                    <label>Penerbit</label>
                    <input type="text" name="penerbit" class="form-control" value="{{ old('penerbit', $buku->penerbit) }}">
                </div>
                <div class="form-group mb-3">
                    <label>Tahun Terbit</label>
                    <input type="number" name="tahun_terbit" class="form-control" value="{{ old('tahun_terbit', $buku->tahun_terbit) }}">
                </div>
                <div class="form-group mb-3">
                    <label>ISBN</label>
                    <input type="text" name="isbn" class="form-control" value="{{ old('isbn', $buku->isbn) }}">
                </div>
                <div class="form-group mb-3">
                    <label>Kategori</label>
                    <select name="kategori_id" class="form-control @error('kategori_id') is-invalid @enderror">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($kategoris as $kategori)
                            <option value="{{ $kategori->id }}" {{ old('kategori_id', $buku->kategori_id) == $kategori->id ? 'selected' : '' }}>
                                {{ $kategori->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                    @error('kategori_id') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="form-group mb-3">
                    <label>Stok</label>
                    <input type="number" name="stok" class="form-control @error('stok') is-invalid @enderror" value="{{ old('stok', $buku->stok) }}">
                    @error('stok') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="form-group mb-3">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $buku->deskripsi) }}</textarea>
                </div>
                <div class="form-group mb-3">
                    <label>Cover Buku</label>
                    @if ($buku->cover)
                        <div class="mb-2"><img src="{{ asset('storage/' . $buku->cover) }}" width="80"></div>
                    @endif
                    <input type="file" name="cover" class="form-control @error('cover') is-invalid @enderror">
                    <small class="text-muted">Kosongkan kalau tidak ingin ganti cover.</small>
                    @error('cover') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('admin.buku.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
@endsection