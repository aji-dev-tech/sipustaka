<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // NIS/NIP dipakai sebagai identitas anggota: harus unik di level database,
            // bukan cuma di validasi controller (validasi saja bisa lolos kalau ada klik ganda).
            // MySQL mengizinkan banyak NULL pada kolom unique, jadi aman untuk yang belum diisi.
            $table->string('nis_nip')->nullable()->unique()->after('name');
            $table->enum('role', ['admin', 'siswa'])->default('siswa')->after('nis_nip');
            $table->string('no_telp')->nullable()->after('role');
            $table->text('alamat')->nullable()->after('no_telp');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['nis_nip']);
            $table->dropColumn(['nis_nip', 'role', 'no_telp', 'alamat']);
        });
    }
};
