# SIPUSTAKA

**Sistem Informasi Perpustakaan SMK Negeri 5 Surakarta**
Website peminjaman buku perpustakaan secara online, dibangun dengan Laravel 12.

## Identitas

| | |
|---|---|
| Nama | Ajimas Aptanegara |
| Kelas | XII RPL A |
| Sekolah | SMK Negeri 5 Surakarta |
| Mata pelajaran | KK-RPL (Pemrograman Teks, Grafik, dan Multimedia) |
| Jenis tugas | Project Akhir |
| Nama project | SIPUSTAKA |
| Website | https://sipustaka.tefarpl.my.id |
| Repository | https://github.com/aji-dev-tech/sipustaka |

## Deskripsi

Pencatatan peminjaman buku secara manual memakan waktu dan mudah salah catat. SIPUSTAKA memudahkan siswa mencari dan meminjam buku secara online, serta membantu admin memverifikasi pengajuan, mencatat pengembalian dan denda, dan mencetak laporan.

## Fitur

**Siswa**
- Login dan dashboard ringkasan (sedang dipinjam, menunggu verifikasi, denda)
- Katalog buku dengan cover dan pencarian
- Keranjang: mengajukan beberapa buku sekaligus (maksimal 10 buku)
- Lama peminjaman maksimal 7 hari
- Riwayat peminjaman, status, dan denda

**Admin**
- Dashboard statistik dan aktivitas peminjaman terbaru
- Master Buku (tambah, edit, hapus, upload cover JPG/PNG maks 2 MB)
- Master Kategori dan Data Anggota
- Verifikasi pengajuan (setujui atau tolak) dengan pengecekan stok
- Pengembalian buku: kondisi buku, denda terlambat otomatis, denda rusak, dan denda custom
- Pengaturan tarif denda per hari
- Laporan

## Teknologi

- PHP 8.2+, Laravel 12
- Blade, AdminLTE 4 (Bootstrap 5)
- MySQL (XAMPP untuk pengembangan lokal)

## Aturan Peminjaman

| Aturan | Nilai |
|---|---|
| Lama pinjam maksimal | 7 hari |
| Buku per pengajuan | maksimal 10 |
| Denda terlambat | hari telat x tarif per hari (default Rp 1.000, diatur admin) |
| Cover buku | JPG, JPEG, PNG, maksimal 2 MB |

Aturan ini diatur di `config/sipustaka.php` dan dicek di sisi server.

## Cara Instalasi (lokal)

1. Clone repository
   ```
   git clone https://github.com/aji-dev-tech/sipustaka.git
   cd sipustaka
   ```
2. Install dependensi
   ```
   composer install
   ```
3. Salin file environment lalu buat app key
   ```
   cp .env.example .env
   php artisan key:generate
   ```
4. Buat database MySQL, lalu isi `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` di `.env`
5. Jalankan migrasi dan seeder
   ```
   php artisan migrate --seed
   ```
6. Hubungkan folder penyimpanan agar cover buku tampil
   ```
   php artisan storage:link
   ```
7. Jalankan aplikasi
   ```
   php artisan serve
   ```
   Buka http://127.0.0.1:8000

## Akun Demo (dari seeder)

| Peran | Email | Password |
|---|---|---|
| Admin | admin@sipustaka.test | password |
| Siswa | ajimas@sipustaka.test | password |

Ganti password default sebelum dipakai sungguhan.

## Struktur Singkat

```
app/Http/Controllers/Admin   Controller admin (buku, kategori, anggota, verifikasi, laporan, pengaturan)
app/Http/Controllers/Siswa   Controller siswa (dashboard, katalog, keranjang, peminjaman)
app/Http/Controllers/Auth    Login dan logout
config/sipustaka.php         Aturan peminjaman
database/migrations          Struktur tabel
resources/views              Tampilan Blade
routes/web.php               Daftar route
```

## Lisensi

Dibuat untuk keperluan tugas sekolah.
