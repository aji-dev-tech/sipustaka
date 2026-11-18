# SIPUSTAKA — Panduan Perbaikan

> Catatan jujur: perbaikan kode ini sudah saya periksa secara statis (kurung/kurawal, Blade, nama route),
> tetapi **belum dijalankan** karena lingkungan saya tidak punya PHP maupun MySQL. Jalankan checklist
> di bagian 5 setelah menerapkan.

## 1. Apa yang sebenarnya terjadi pada MySQL-mu (dari `laravel.log`)

| Waktu | Error | Artinya |
|---|---|---|
| 19 Sep, 21 Okt, 4 Nov 04:16 | `2002 ... actively refused` | MySQL **tidak sedang berjalan** (XAMPP belum di-Start / mati sendiri). |
| 4 Nov 00:27–00:46 | `1130 Host 'localhost' is not allowed` | Akun MySQL yang dipakai tidak boleh login dari host itu (akun `root@localhost` hilang/berubah). |
| 4 Nov 05:18–05:25 | `1932 Table 'db_sipustaka.sessions' doesn't exist in engine` | **Tablespace InnoDB rusak/tidak sinkron**: file tabel ada di folder `data`, tapi MySQL tidak mengenalinya. Biasanya akibat MySQL dimatikan paksa atau folder `data` disalin/diganti. |
| 4 Nov 05:31–05:35 | `1146 Table 'db_test.sessions' doesn't exist` | Kamu pindah ke `db_test`. **Koneksi sudah berhasil** (error-nya berubah), tapi database kosong: migrasi belum dijalankan. |

Kenapa seluruh situs mati, termasuk halaman login? Karena `SESSION_DRIVER`, `CACHE_STORE`, dan `QUEUE_CONNECTION`
semuanya `database`. Setiap request membaca tabel `sessions` dulu, jadi kalau MySQL bermasalah, tidak ada halaman yang bisa dibuka.

## 2. Cara tercepat memulihkan (disarankan)

Tidak menyentuh `db_sipustaka` yang rusak. Data bawaan (2 user, 6 kategori, 7 buku) dibuat ulang oleh seeder.

**a. Start MySQL di XAMPP.** Kalau langsung mati lagi, baca `E:\XAMPP\mysql\data\mysql_error.log` (baris terakhir).

**b. phpMyAdmin → tab SQL → jalankan** (ganti password):

```sql
CREATE DATABASE db_sipustaka_baru CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'sipustaka'@'localhost' IDENTIFIED BY 'GANTI_PASSWORD_INI';
CREATE USER 'sipustaka'@'127.0.0.1' IDENTIFIED BY 'GANTI_PASSWORD_INI';
GRANT ALL PRIVILEGES ON db_sipustaka_baru.* TO 'sipustaka'@'localhost';
GRANT ALL PRIVILEGES ON db_sipustaka_baru.* TO 'sipustaka'@'127.0.0.1';
FLUSH PRIVILEGES;
```

Memakai user khusus (bukan `root` tanpa password) sekaligus menghindari error 1130.

**c. Edit `.env`** (jangan ubah `APP_KEY`). Kalau password mengandung spasi atau `#`, apit dengan tanda kutip:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_sipustaka_baru
DB_USERNAME=sipustaka
DB_PASSWORD=GANTI_PASSWORD_INI

SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync
```

`127.0.0.1` lebih aman daripada `localhost` di Windows (menghindari resolusi IPv6/hostname).
Session/cache memakai file supaya halaman login tetap terbuka walau MySQL sedang bermasalah.

**d. Jalankan di folder proyek** (`E:\XAMPP\htdocs\sipustaka`):

```
php artisan config:clear
php artisan migrate:fresh --seed
php artisan storage:link
php artisan optimize:clear
```

- `migrate:fresh` **menghapus semua tabel** di database yang dikonfigurasi. Aman di sini karena databasenya baru,
  jangan dijalankan di database yang berisi data penting.
- Migrasi lama sudah saya ubah (bukan ditambah migrasi baru) karena databasenya memang dibangun ulang.
  Jangan pakai `php artisan migrate` biasa di database yang sudah berisi tabel versi lama.
- `storage:link` wajib supaya cover buku tampil (`public/storage` belum ada di proyekmu).
- File cover lama di `storage/app/public/buku/` masih ada, tapi baris datanya di database hilang; upload ulang lewat Master Buku.

Login awal: `admin@sipustaka.test` / `password` dan `ajimas@sipustaka.test` / `password`. **Ganti sebelum dipakai sungguhan.**

## 3. Kalau kamu butuh data lama di `db_sipustaka` (opsional, berisiko)

Hanya kalau datanya benar-benar penting. Ini resep umum pemulihan XAMPP, bukan sesuatu yang bisa saya uji di mesinmu:

1. Stop MySQL. **Salin seluruh `E:\XAMPP\mysql\data` ke tempat aman** sebagai cadangan.
2. Ganti nama `mysql\data` menjadi `mysql\data_lama`, lalu salin folder `mysql\backup` menjadi `mysql\data` yang baru.
3. Dari `data_lama`, salin ke `data` yang baru: folder `db_sipustaka` (dan database milikmu yang lain) serta file `ibdata1`.
   Jangan salin folder `mysql`, `performance_schema`, `phpmyadmin`.
4. Start MySQL. Akun user direset (root tanpa password), jadi ulangi langkah 2b.
5. Kalau tabel masih error 1932, datanya tidak bisa dipulihkan dengan cara menyalin; pakai bagian 2.

## 4. Perubahan kode

| File | Perubahan | Alasan |
|---|---|---|
| `config/app.php` | timezone `UTC` → `Asia/Jakarta` | Denda dan "tanggal hari ini" meleset 7 jam (pengembalian pagi dihitung kemarin). |
| migrasi `bukus` | `stok` unsigned; FK kategori `restrict` | Stok tidak bisa negatif; hapus kategori tidak menghapus semua bukunya. |
| migrasi `peminjamen` | FK user/buku `restrict`; index `status`, `(user_id,status)` | Hapus anggota tidak lagi menghapus riwayat + denda; query filter lebih cepat. |
| migrasi `dendas` | `peminjaman_id` unique | Satu peminjaman satu denda (model memakai `hasOne`). |
| migrasi `users` | `nis_nip` unique | Validasi unik di controller saja bisa lolos saat klik ganda. |
| `VerifikasiController` | approve/kembalikan dalam transaksi + `lockForUpdate`; reject atomik; method `lunasi` baru | Mencegah stok negatif / diproses dua kali / denda ganda. Sebelumnya denda tidak bisa dilunasi sama sekali. |
| `AppServiceProvider` | `Route::bind('anggota')` dibatasi role `siswa` | Mengganti ID di URL bisa mengedit/menghapus akun admin (termasuk diri sendiri). |
| `AnggotaController` | hapus anggota diblok bila punya riwayat | Sebelumnya cascade menghapus riwayat + denda diam-diam. |
| `Siswa\PeminjamanController` | cegah pengajuan ganda untuk buku yang sama; eager load `denda` | Satu siswa bisa mengajukan berkali-kali; N+1 query di riwayat. |
| `Admin\DashboardController` | hapus variabel `$totalBuku` yang tidak dipakai | Dead code + query sia-sia. |
| `routes/web.php` | route `/home`; `throttle:10,1` di POST login; route `denda.lunas` | Logo AdminLTE menuju `/home` (404); login tanpa pembatasan percobaan. |
| view `verifikasi/index`, `anggota/index` | tombol "Tandai Lunas" + status denda; alert error | Mendukung perubahan di atas. |
| seeder (3 file) | `firstOrCreate`, kategori dicari lewat nama | Seeder dijalankan ulang tidak lagi duplikat/gagal; ID kategori tidak di-hardcode. |
| `.env.example` | MySQL + session/cache file + queue sync | Default yang tidak bergantung pada tabel database. |

## 5. Checklist uji (±10 menit)

1. Login admin → dashboard tampil tanpa error. Klik logo AdminLTE → tetap di dashboard sesuai role.
2. Login siswa → ajukan buku A → ajukan buku A lagi → ditolak dengan pesan.
3. Admin: Approve → stok buku turun 1. "Tandai Kembali" → stok naik 1.
4. Uji denda: di phpMyAdmin ubah `tgl_kembali_rencana` pengajuan yang `approved` ke kemarin, lalu "Tandai Kembali"
   → denda Rp 1.000 muncul → klik "Tandai Lunas" → dashboard siswa: denda kembali Rp 0.
5. Hapus anggota yang punya riwayat → pesan error. Buka `/admin/anggota/1/edit` (ID admin) → 404.
6. Cover buku tampil setelah `storage:link`.

## 6. Sengaja tidak saya ubah (keputusanmu)

- **Blokir pinjam saat ada denda belum lunas** dan **batas jumlah buku per siswa**: aturan bisnis, belum ada di spesifikasi.
- **Bahasa pesan validasi**: `APP_LOCALE=en` dan folder `lang/` belum punya terjemahan Indonesia, jadi pesan validasi masih Inggris.
- **`APP_DEBUG=true`**: nyalakan `false` saat dipakai di jaringan sekolah supaya detail error tidak terlihat siswa.
- **Password seeder `password`**: ganti setelah login pertama.
