<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $kategoris = ['Fiksi', 'Non-Fiksi', 'Sains & Teknologi', 'Sejarah', 'Komik', 'Pelajaran Sekolah'];

        // firstOrCreate: aman dijalankan berulang, tidak membuat kategori ganda.
        foreach ($kategoris as $nama) {
            Kategori::firstOrCreate(['nama_kategori' => $nama]);
        }
    }
}
