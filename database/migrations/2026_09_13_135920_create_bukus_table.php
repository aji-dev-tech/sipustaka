<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bukus', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('penulis');
            $table->string('penerbit')->nullable();
            $table->year('tahun_terbit')->nullable();
            $table->string('isbn')->nullable();
            // restrict, bukan cascade: menghapus kategori tidak boleh diam-diam
            // menghapus semua buku (dan riwayat peminjamannya) di bawahnya.
            $table->foreignId('kategori_id')->constrained('kategoris')->restrictOnDelete();
            // unsigned: stok tidak mungkin negatif walaupun ada bug di aplikasi.
            $table->unsignedInteger('stok')->default(0);
            $table->text('deskripsi')->nullable();
            $table->string('cover')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bukus');
    }
};
