@extends('adminlte::page')

@section('title', 'Peminjaman Saya - SIPUSTAKA')

@section('content_header')
    <h1>Riwayat Peminjaman Saya</h1>
@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Buku</th>
                        <th>Tgl Pinjam</th>
                        <th>Rencana Kembali</th>
                        <th>Status</th>
                        <th>Catatan Admin</th>
                        <th>Denda</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($peminjamen as $p)
                        <tr>
                            <td>{{ $p->buku->judul ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d-m-Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($p->tgl_kembali_rencana)->format('d-m-Y') }}</td>
                            <td>
                                @php
                                    $badge = match($p->status) {
                                        'pending' => 'warning',
                                        'approved', 'dipinjam' => 'success',
                                        'rejected' => 'danger',
                                        'dikembalikan' => 'secondary',
                                        default => 'light',
                                    };
                                @endphp
                                <span class="badge bg-{{ $badge }}">{{ ucfirst($p->status) }}</span>
                            </td>
                            <td>{{ $p->catatan_admin ?? '-' }}</td>
                            <td>
                                @if ($p->denda)
                                    <span class="text-danger">Rp {{ number_format($p->denda->nominal_denda, 0, ',', '.') }}</span>
                                    <br><small>({{ $p->denda->jumlah_hari_telat }} hari telat)</small>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center">Belum ada riwayat peminjaman.</td></tr>
                    @endforelse
                </tbody>
            </table>
            {{ $peminjamen->links() }}
        </div>
    </div>
@endsection