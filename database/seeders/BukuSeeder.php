<?php

namespace Database\Seeders;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Database\Seeder;

class BukuSeeder extends Seeder
{
    public function run(): void
    {
        // Cari kategori berdasarkan NAMA, bukan ID hardcode. Kalau tabel pernah di-seed/di-reset,
        // AUTO_INCREMENT tidak kembali ke 1 dan ID 1-6 tidak lagi ada -> foreign key error.
        $kategori = Kategori::pluck('id', 'nama_kategori');

        $bukus = [
            ['judul' => 'Laskar Pelangi', 'penulis' => 'Andrea Hirata', 'kategori' => 'Fiksi', 'stok' => 5, 'tahun_terbit' => 2005],
            ['judul' => 'Bumi Manusia', 'penulis' => 'Pramoedya Ananta Toer', 'kategori' => 'Fiksi', 'stok' => 3, 'tahun_terbit' => 1980],
            ['judul' => 'Sapiens', 'penulis' => 'Yuval Noah Harari', 'kategori' => 'Non-Fiksi', 'stok' => 4, 'tahun_terbit' => 2011],
            ['judul' => 'Belajar Python Dasar', 'penulis' => 'Tim Penerbit', 'kategori' => 'Sains & Teknologi', 'stok' => 6, 'tahun_terbit' => 2020],
            ['judul' => 'Sejarah Kerajaan Nusantara', 'penulis' => 'Tim Penerbit', 'kategori' => 'Sejarah', 'stok' => 2, 'tahun_terbit' => 2015],
            ['judul' => 'One Piece Vol. 1', 'penulis' => 'Eiichiro Oda', 'kategori' => 'Komik', 'stok' => 7, 'tahun_terbit' => 1997],
            ['judul' => 'Matematika Kelas XII', 'penulis' => 'Kemendikbud', 'kategori' => 'Pelajaran Sekolah', 'stok' => 10, 'tahun_terbit' => 2023],
        ];

        foreach ($bukus as $b) {
            $namaKategori = $b['kategori'];
            unset($b['kategori']);

            Buku::firstOrCreate(
                ['judul' => $b['judul'], 'penulis' => $b['penulis']],
                $b + ['kategori_id' => $kategori[$namaKategori]]
            );
        }
    }
}
