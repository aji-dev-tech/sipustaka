<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dendas', function (Blueprint $table) {
            $table->id();
            // unique: model Peminjaman memakai hasOne(Denda), jadi satu peminjaman
            // hanya boleh punya satu denda (mencegah denda dobel kalau tombol diklik 2x).
            $table->foreignId('peminjaman_id')->unique()->constrained('peminjamen')->cascadeOnDelete();
            $table->integer('jumlah_hari_telat')->default(0);
            $table->integer('nominal_denda')->default(0);
            $table->enum('status_bayar', ['belum_bayar', 'lunas'])->default('belum_bayar');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dendas');
    }
};
