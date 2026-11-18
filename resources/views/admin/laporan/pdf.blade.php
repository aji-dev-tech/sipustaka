<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Peminjaman</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #333; padding: 6px 8px; text-align: left; }
        th { background: #2F5597; color: white; }
        h2 { margin-bottom: 0; }
        .sub { color: #555; margin-top: 2px; }
    </style>
</head>
<body>
    <h2>Laporan Peminjaman Buku - SIPUSTAKA</h2>
    <p class="sub">SMK Negeri 5 Surakarta | Dicetak: {{ now()->format('d-m-Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Siswa</th>
                <th>Buku</th>
                <th>Tgl Pinjam</th>
                <th>Rencana Kembali</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($peminjamen as $i => $p)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $p->user->name ?? '-' }}</td>
                    <td>{{ $p->buku->judul ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d-m-Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($p->tgl_kembali_rencana)->format('d-m-Y') }}</td>
                    <td>{{ ucfirst($p->status) }}</td>
                </tr>
            @empty
                <tr><td colspan="6" style="text-align:center;">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>