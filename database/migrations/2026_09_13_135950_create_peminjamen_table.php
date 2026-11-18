<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peminjamen', function (Blueprint $table) {
            $table->id();
            // restrict, bukan cascade: riwayat peminjaman (dan denda) adalah catatan
            // keuangan, tidak boleh hilang hanya karena anggota/buku dihapus.
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('buku_id')->constrained('bukus')->restrictOnDelete();
            $table->date('tanggal_pinjam');
            $table->date('tgl_kembali_rencana');
            $table->date('tgl_kembali_aktual')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'dipinjam', 'dikembalikan'])->default('pending');
            $table->text('catatan_admin')->nullable();
            $table->timestamps();

            // Index untuk query yang benar-benar dipakai aplikasi:
            // filter status (verifikasi/laporan/dashboard) dan "pinjaman aktif milik user X".
            $table->index('status');
            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjamen');
    }
};
