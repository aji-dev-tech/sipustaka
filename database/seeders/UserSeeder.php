<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // firstOrCreate (kunci: email): aman dijalankan berulang dan TIDAK menimpa
        // password yang sudah diganti. Ganti password default ini sebelum dipakai sungguhan.
        User::firstOrCreate(['email' => 'admin@sipustaka.test'], [
            'name' => 'Admin Perpustakaan',
            'nis_nip' => '198001012024',
            'role' => 'admin',
            'no_telp' => '081234567890',
            'alamat' => 'SMK Negeri 5 Surakarta',
            'password' => Hash::make('password'),
        ]);

        User::firstOrCreate(['email' => 'ajimas@sipustaka.test'], [
            'name' => 'Ajimas Aptanegara',
            'nis_nip' => '2425.02',
            'role' => 'siswa',
            'no_telp' => '081298765432',
            'alamat' => 'Surakarta',
            'password' => Hash::make('password'),
        ]);
    }
}
