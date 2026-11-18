# Deploy SIPUSTAKA dengan Docker

Web: http://IP_SERVER:8089  |  MySQL (dari server): 127.0.0.1:8010

1. Salin isi folder ini (Dockerfile, docker-compose.yml, .dockerignore, .env.*.example, folder docker/) ke root proyek sipustaka di server.
2. `cp .env.docker.example .env.docker && cp .env.db.example .env.db`
3. Edit kedua file: isi APP_KEY, APP_URL, dan password (DB_PASSWORD di .env.docker harus sama dengan MYSQL_PASSWORD di .env.db).
4. `docker compose up -d --build`
5. `docker compose logs -f web`  (tunggu pesan "SIPUSTAKA siap.")
6. Login: admin@sipustaka.test / password -> segera ganti, lalu set RUN_SEED=false.

Perintah berguna:
- Restart: `docker compose restart`
- Update kode: `docker compose up -d --build`
- Backup DB: `docker compose exec db sh -c 'mysqldump -uroot -p"$MYSQL_ROOT_PASSWORD" db_sipustaka' > backup.sql`
- Stop: `docker compose down`  (data tetap aman; `down -v` MENGHAPUS data)
