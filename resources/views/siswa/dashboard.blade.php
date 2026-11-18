@extends('adminlte::page')

@section('title', 'Dashboard Siswa - SIPUSTAKA')

@section('content_header')
    <h1>Dashboard Siswa</h1>
@endsection

@section('content')
    <p>Selamat datang, <strong>{{ auth()->user()->name }}</strong> 👋</p>

    <div class="row">
        <div class="col-lg-4 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $sedangDipinjam }}</h3>
                    <p>Sedang Dipinjam</p>
                </div>
                <div class="icon"><i class="bi bi-journal-check"></i></div>
            </div>
        </div>
        <div class="col-lg-4 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $menungguVerifikasi }}</h3>
                    <p>Menunggu Verifikasi</p>
                </div>
                <div class="icon"><i class="bi bi-hourglass-split"></i></div>
            </div>
        </div>
        <div class="col-lg-4 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>Rp {{ number_format($totalDenda, 0, ',', '.') }}</h3>
                    <p>Denda Belum Dibayar</p>
                </div>
                <div class="icon"><i class="bi bi-exclamation-triangle"></i></div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <a href="{{ route('siswa.katalog.index') }}" class="btn btn-primary btn-sm">Lihat Katalog Buku</a>
            <a href="{{ route('siswa.peminjaman.index') }}" class="btn btn-info btn-sm">Riwayat Peminjaman</a>
            <form method="POST" action="/logout" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm">Logout</button>
            </form>
        </div>
    </div>
@endsection
