#!/bin/sh
set -e
PW="server123"

# 1. Samakan password di kedua file konfigurasi
sed -i "s/^MYSQL_ROOT_PASSWORD=.*/MYSQL_ROOT_PASSWORD=$PW/" .env.db
sed -i "s/^MYSQL_PASSWORD=.*/MYSQL_PASSWORD=$PW/"           .env.db
sed -i "s/^DB_PASSWORD=.*/DB_PASSWORD=$PW/"                 .env.docker

echo ">> Isi password sekarang:"
grep -H "PASSWORD" .env.db .env.docker

# 2. Hapus container + volume lama, lalu buat ulang dengan password baru
docker compose down -v
docker compose up -d

# 3. Tunggu sampai aplikasi siap
echo ">> Menunggu aplikasi siap (Ctrl+C untuk berhenti melihat log)..."
docker compose logs -f web
