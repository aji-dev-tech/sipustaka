@extends('adminlte::page')

@section('title', 'Ajukan Peminjaman - SIPUSTAKA')

@section('content_header')
    <h1>Form Pengajuan Peminjaman</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="alert alert-info">
                <strong>Buku dipilih:</strong> {{ $buku->judul }} — {{ $buku->penulis }}
                <br><small>Stok tersedia: {{ $buku->stok }}</small>
            </div>

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form action="{{ route('siswa.peminjaman.store') }}" method="POST">
                @csrf
                <input type="hidden" name="buku_id" value="{{ $buku->id }}">

                <div class="form-group mb-3">
                    <label>Tanggal Pinjam</label>
                    <input type="date" name="tanggal_pinjam" class="form-control @error('tanggal_pinjam') is-invalid @enderror" value="{{ old('tanggal_pinjam', date('Y-m-d')) }}">
                    @error('tanggal_pinjam') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="form-group mb-3">
                    <label>Tanggal Rencana Kembali</label>
                    <input type="date" name="tgl_kembali_rencana" class="form-control @error('tgl_kembali_rencana') is-invalid @enderror" value="{{ old('tgl_kembali_rencana') }}">
                    @error('tgl_kembali_rencana') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="btn btn-primary">Ajukan</button>
                <a href="{{ route('siswa.katalog.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
@endsection