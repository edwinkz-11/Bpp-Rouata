#!/usr/bin/env bash
# Dijalankan di dalam container: membuat proyek Laravel pada run pertama, lalu menyalakan server.
set -euo pipefail
cd /var/www

if [ ! -f app/artisan ] && [ ! -f artisan ]; then
  composer create-project laravel/laravel /tmp/laravel --prefer-dist --no-interaction
  cp -a /tmp/laravel/. /var/www/
  rm -rf /tmp/laravel
fi

# Tempel kode BPP Routa (di-mount ke /kode)
for d in app database lang public resources routes; do
  mkdir -p "/var/www/$d"; cp -R "/kode/$d/." "/var/www/$d/"
done

cat > .env <<EOF
APP_NAME="BPP Pertanian Routa"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=${APP_URL:-http://localhost:8000}
APP_LOCALE=id
APP_FALLBACK_LOCALE=en
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=bpp_pertanian_routa
DB_USERNAME=bpp
DB_PASSWORD=${DB_PASSWORD:-bpp_secret}
SESSION_DRIVER=database
FILESYSTEM_DISK=public
ADMIN_NAME="Administrator BPP Routa"
ADMIN_EMAIL=${ADMIN_EMAIL:-admin@bpp-routa.test}
ADMIN_PASSWORD=${ADMIN_PASSWORD:-GantiSandiIni123}
EOF

php artisan key:generate --force

until php -r 'new PDO("mysql:host=db;dbname=bpp_pertanian_routa", "bpp", getenv("DB_PASSWORD") ?: "bpp_secret");' 2>/dev/null; do
  echo "Menunggu MySQL..."; sleep 3
done

php artisan migrate --seed --force
php artisan storage:link || true
exec php artisan serve --host=0.0.0.0 --port=8000
