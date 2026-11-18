#!/bin/sh
set -e
cd /var/www/html

if [ -z "$APP_KEY" ]; then
  echo "ERROR: APP_KEY kosong. Isi di file .env.docker (lihat README-DEPLOY.md)." >&2
  exit 1
fi

art() { runuser -u www-data -- php artisan "$@"; }

echo ">> Menunggu MySQL di ${DB_HOST}:${DB_PORT} ..."
i=0
until php -r 'try { new PDO("mysql:host=".getenv("DB_HOST").";port=".getenv("DB_PORT").";dbname=".getenv("DB_DATABASE"), getenv("DB_USERNAME"), getenv("DB_PASSWORD")); } catch (Exception $e) { exit(1); }'; do
  i=$((i+1))
  if [ "$i" -ge 60 ]; then echo "ERROR: MySQL tidak bisa dihubungi." >&2; exit 1; fi
  sleep 2
done
echo ">> MySQL siap."

mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
chmod -R ug+rwX storage bootstrap/cache

art package:discover --ansi
art migrate --force

# Seeder memakai firstOrCreate, aman diulang dan tidak menimpa password yang sudah diganti.
if [ "${RUN_SEED:-true}" = "true" ]; then
  art db:seed --force
fi

php artisan storage:link --force
art config:cache
art route:cache
art view:cache

echo ">> SIPUSTAKA siap."
exec "$@"
