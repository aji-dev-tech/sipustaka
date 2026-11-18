@extends('adminlte::page')

@section('title', 'Data Anggota - SIPUSTAKA')

@section('content_header')
    <h1>Data Anggota (Siswa)</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <a href="{{ route('admin.anggota.create') }}" class="btn btn-primary btn-sm">+ Tambah Anggota</a>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th style="width: 60px;">No</th>
                        <th>Nama</th>
                        <th>NIS</th>
                        <th>Email</th>
                        <th>No. Telp</th>
                        <th style="width: 160px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($anggotas as $i => $anggota)
                        <tr>
                            <td>{{ $anggotas->firstItem() + $i }}</td>
                            <td>{{ $anggota->name }}</td>
                            <td>{{ $anggota->nis_nip }}</td>
                            <td>{{ $anggota->email }}</td>
                            <td>{{ $anggota->no_telp ?? '-' }}</td>
                            <td>
                                <a href="{{ route('admin.anggota.edit', $anggota->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('admin.anggota.destroy', $anggota->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus anggota ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center">Belum ada data anggota.</td></tr>
                    @endforelse
                </tbody>
            </table>
            {{ $anggotas->links() }}
        </div>
    </div>
@endsection