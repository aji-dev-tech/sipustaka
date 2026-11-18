@extends('adminlte::page')

@section('title', 'Dashboard Admin - SIPUSTAKA')

@section('content_header')
    <h1>Dashboard Admin</h1>
@endsection

@section('content')
    <p>Selamat datang, <strong>{{ auth()->user()->name }}</strong> 👋</p>

    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $totalJudul }}</h3>
                    <p>Total Judul Buku</p>
                </div>
                <div class="icon"><i class="bi bi-book"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $dipinjam }}</h3>
                    <p>Sedang Dipinjam</p>
                </div>
                <div class="icon"><i class="bi bi-journal-check"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $pending }}</h3>
                    <p>Menunggu Verifikasi</p>
                </div>
                <div class="icon"><i class="bi bi-hourglass-split"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $telat }}</h3>
                    <p>Terlambat Kembali</p>
                </div>
                <div class="icon"><i class="bi bi-exclamation-triangle"></i></div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Aktivitas Peminjaman Terbaru</h3>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>Siswa</th>
                        <th>Buku</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($aktivitasTerbaru as $a)
                        <tr>
                            <td>{{ $a->user->name ?? '-' }}</td>
                            <td>{{ $a->buku->judul ?? '-' }}</td>
                            <td>
                                @php
                                    $badge = match($a->status) {
                                        'pending' => 'warning', 'approved', 'dipinjam' => 'success',
                                        'rejected' => 'danger', 'dikembalikan' => 'secondary', default => 'light',
                                    };
                                @endphp
                                <span class="badge bg-{{ $badge }}">{{ ucfirst($a->status) }}</span>
                            </td>
                            <td>{{ $a->created_at->format('d-m-Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center">Belum ada aktivitas.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection