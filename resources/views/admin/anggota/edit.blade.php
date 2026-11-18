@extends('adminlte::page')

@section('title', 'Edit Anggota - SIPUSTAKA')

@section('content_header')
    <h1>Edit Anggota</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.anggota.update', $anggota->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group mb-3">
                    <label>Nama Lengkap</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $anggota->name) }}">
                    @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="form-group mb-3">
                    <label>NIS</label>
                    <input type="text" name="nis_nip" class="form-control @error('nis_nip') is-invalid @enderror" value="{{ old('nis_nip', $anggota->nis_nip) }}">
                    @error('nis_nip') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="form-group mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $anggota->email) }}">
                    @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="form-group mb-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                    <small class="text-muted">Kosongkan kalau tidak ingin ganti password.</small>
                    @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="form-group mb-3">
                    <label>No. Telp</label>
                    <input type="text" name="no_telp" class="form-control" value="{{ old('no_telp', $anggota->no_telp) }}">
                </div>
                <div class="form-group mb-3">
                    <label>Alamat</label>
                    <textarea name="alamat" class="form-control" rows="2">{{ old('alamat', $anggota->alamat) }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('admin.anggota.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
@endsection