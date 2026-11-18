<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\BukuController;
use App\Http\Controllers\Admin\AnggotaController;
use App\Http\Controllers\Admin\VerifikasiController;
use App\Http\Controllers\Siswa\KatalogController;
use App\Http\Controllers\Siswa\PeminjamanController;
use App\Http\Controllers\Siswa\DashboardController as SiswaDashboardController;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
// Batasi percobaan login (10x per menit per IP) supaya password tidak bisa ditebak beruntun.
Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:10,1');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {

    // Dipakai logo/tombol "home" bawaan AdminLTE (config: dashboard_url => 'home').
    Route::get('/home', function () {
        return redirect()->route(auth()->user()->isAdmin() ? 'admin.dashboard' : 'siswa.dashboard');
    })->name('home');

    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

        Route::resource('kategori', KategoriController::class);
        Route::resource('buku', BukuController::class);
        Route::resource('anggota', AnggotaController::class)->parameters(['anggota' => 'anggota']);

        Route::get('/verifikasi', [VerifikasiController::class, 'index'])->name('verifikasi.index');
        Route::post('/verifikasi/{peminjaman}/approve', [VerifikasiController::class, 'approve'])->name('verifikasi.approve');
        Route::post('/verifikasi/{peminjaman}/reject', [VerifikasiController::class, 'reject'])->name('verifikasi.reject');
        Route::post('/verifikasi/{peminjaman}/kembalikan', [VerifikasiController::class, 'kembalikan'])->name('verifikasi.kembalikan');
        Route::post('/denda/{denda}/lunas', [VerifikasiController::class, 'lunasi'])->name('denda.lunas');
        Route::get('/laporan', [\App\Http\Controllers\Admin\LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/cetak', [\App\Http\Controllers\Admin\LaporanController::class, 'cetakPdf'])->name('laporan.cetak');
    });

    Route::middleware(['role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {
        Route::get('/dashboard', [SiswaDashboardController::class, 'index'])->name('dashboard');

        Route::get('/katalog', [KatalogController::class, 'index'])->name('katalog.index');
        Route::get('/peminjaman/ajukan/{buku}', [PeminjamanController::class, 'create'])->name('peminjaman.create');
        Route::post('/peminjaman', [PeminjamanController::class, 'store'])->name('peminjaman.store');
        Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
    });

});