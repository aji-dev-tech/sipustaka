@extends('adminlte::page')

@section('title', 'Laporan Peminjaman - SIPUSTAKA')

@section('content_header')
    <h1>Laporan Peminjaman</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="GET" class="row g-2 mb-3">
                <div class="col-md-3">
                    <label class="small">Dari Tanggal</label>
                    <input type="date" name="dari" class="form-control" value="{{ request('dari') }}">
                </div>
                <div class="col-md-3">
                    <label class="small">Sampai Tanggal</label>
                    <input type="date" name="sampai" class="form-control" value="{{ request('sampai') }}">
                </div>
                <div class="col-md-3">
                    <label class="small">Status</label>
                    <select name="status" class="form-control">
                        <option value="">-- Semua --</option>
                        @foreach (['pending', 'approved', 'rejected', 'dipinjam', 'dikembalikan'] as $s)
                            <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('admin.laporan.cetak', request()->query()) }}" class="btn btn-danger">
                        <i class="bi bi-file-earmark-pdf"></i> Cetak PDF
                    </a>
                </div>
            </form>

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Siswa</th>
                        <th>Buku</th>
                        <th>Tgl Pinjam</th>
                        <th>Rencana Kembali</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($peminjamen as $p)
                        <tr>
                            <td>{{ $p->user->name ?? '-' }}</td>
                            <td>{{ $p->buku->judul ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d-m-Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($p->tgl_kembali_rencana)->format('d-m-Y') }}</td>
                            <td>{{ ucfirst($p->status) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center">Tidak ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
            {{ $peminjamen->links() }}
        </div>
    </div>
@endsection