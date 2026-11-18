@extends('adminlte::page')

@section('title', 'Verifikasi Peminjaman - SIPUSTAKA')

@section('content_header')
    <h1>Verifikasi Pengajuan Peminjaman</h1>
@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="card-header">
            <form method="GET" class="d-flex gap-2">
                <select name="status" class="form-control" style="max-width: 220px;" onchange="this.form.submit()">
                    <option value="">-- Semua Status --</option>
                    @foreach (['pending', 'approved', 'rejected', 'dipinjam', 'dikembalikan'] as $s)
                        <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </form>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Siswa</th>
                        <th>Buku</th>
                        <th>Tgl Pinjam</th>
                        <th>Rencana Kembali</th>
                        <th>Status</th>
                        <th style="width: 220px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($peminjamen as $p)
                        <tr>
                            <td>{{ $p->user->name ?? '-' }}</td>
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
                            <td>
                                @if ($p->status === 'pending')
                                    <form action="{{ route('admin.verifikasi.approve', $p->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Setujui peminjaman ini?')">Approve</button>
                                    </form>
                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $p->id }}">Reject</button>

                                    <div class="modal fade" id="rejectModal{{ $p->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('admin.verifikasi.reject', $p->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Tolak Pengajuan</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <label>Alasan penolakan:</label>
                                                        <textarea name="catatan_admin" class="form-control" rows="3" required></textarea>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-danger">Tolak</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @elseif (in_array($p->status, ['approved', 'dipinjam']))
                                    <form action="{{ route('admin.verifikasi.kembalikan', $p->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('Tandai buku ini sudah dikembalikan?')">Tandai Kembali</button>
                                    </form>
                                @elseif ($p->status === 'dikembalikan' && $p->denda && $p->denda->status_bayar === 'belum_bayar')
                                    <span class="text-danger d-block">Denda Rp {{ number_format($p->denda->nominal_denda, 0, ',', '.') }}</span>
                                    <form action="{{ route('admin.denda.lunas', $p->denda->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-success btn-sm" onclick="return confirm('Tandai denda ini sudah dibayar lunas?')">Tandai Lunas</button>
                                    </form>
                                @elseif ($p->status === 'dikembalikan' && $p->denda)
                                    <span class="text-success">Denda lunas</span>
                                @else
                                    <span class="text-muted">Sudah diproses</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center">Belum ada pengajuan.</td></tr>
                    @endforelse
                </tbody>
            </table>
            {{ $peminjamen->links() }}
        </div>
    </div>
@endsection