#!/usr/bin/env bash
# Setup otomatis BPP Routa: membuat proyek Laravel, menempelkan kode,
# mengatur .env, membuat database, migrasi + seed, storage:link.
#
# Pemakaian:  ./setup.sh [nama-folder]        (bawaan: bpp-routa)
# Variabel opsional: DB_USER DB_PASS DB_HOST ADMIN_NAME ADMIN_EMAIL ADMIN_PASSWORD APP_URL
set -euo pipefail

HERE="$(cd "$(dirname "$0")" && pwd)"
APP_DIR="${1:-bpp-routa}"
DB_NAME="bpp_pertanian_routa"
DB_HOST="${DB_HOST:-127.0.0.1}"
DB_USER="${DB_USER:-root}"
DB_PASS="${DB_PASS:-}"
APP_URL="${APP_URL:-http://127.0.0.1:8000}"
ADMIN_NAME="${ADMIN_NAME:-Administrator BPP Routa}"
ADMIN_EMAIL="${ADMIN_EMAIL:-admin@bpp-routa.test}"
ADMIN_PASSWORD="${ADMIN_PASSWORD:-}"

for bin in php composer mysql; do
  command -v "$bin" >/dev/null 2>&1 || { echo "ERROR: '$bin' belum terpasang." >&2; exit 1; }
done
php -r 'exit(version_compare(PHP_VERSION, "8.2.0", ">=") ? 0 : 1);' || { echo "ERROR: butuh PHP 8.2+." >&2; exit 1; }

if [ -z "$ADMIN_PASSWORD" ]; then
  ADMIN_PASSWORD="$(LC_ALL=C tr -dc 'A-Za-z0-9' </dev/urandom | head -c 14)"
  echo ">> Kata sandi admin dibuat acak: $ADMIN_PASSWORD   (CATAT, lalu ganti setelah login)"
fi

MYSQL=(mysql -h "$DB_HOST" -u "$DB_USER")
[ -n "$DB_PASS" ] && MYSQL+=("-p$DB_PASS")

echo ">> 1/6 Membuat proyek Laravel di ./$APP_DIR"
if [ ! -f "$APP_DIR/artisan" ]; then
  composer create-project laravel/laravel "$APP_DIR" --prefer-dist --no-interaction
fi

echo ">> 2/6 Menempelkan kode BPP Routa"
"$HERE/install.sh" "$APP_DIR" >/dev/null

cd "$APP_DIR"
[ -f .env ] || cp .env.example .env

setenv() {
  local key="$1" val="$2"
  if grep -qE "^#?[[:space:]]*${key}=" .env; then
    sed -i.bak -E "s|^#?[[:space:]]*${key}=.*|${key}=\"${val}\"|" .env
  else
    echo "${key}=\"${val}\"" >> .env
  fi
}

echo ">> 3/6 Mengatur .env"
setenv APP_NAME "BPP Routa"
setenv APP_URL "$APP_URL"
setenv APP_LOCALE "id"
setenv APP_FALLBACK_LOCALE "en"
setenv DB_CONNECTION "mysql"
setenv DB_HOST "$DB_HOST"
setenv DB_PORT "3306"
setenv DB_DATABASE "$DB_NAME"
setenv DB_USERNAME "$DB_USER"
setenv DB_PASSWORD "$DB_PASS"
setenv SESSION_DRIVER "database"
setenv FILESYSTEM_DISK "public"
setenv ADMIN_NAME "$ADMIN_NAME"
setenv ADMIN_EMAIL "$ADMIN_EMAIL"
setenv ADMIN_PASSWORD "$ADMIN_PASSWORD"
rm -f .env.bak

echo ">> 4/6 Membuat database $DB_NAME"
"${MYSQL[@]}" -e "CREATE DATABASE IF NOT EXISTS \`$DB_NAME\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

echo ">> 5/6 Migrasi + seed"
php artisan key:generate --force
php artisan migrate --seed --force
php artisan storage:link || true

echo ">> 6/6 Selesai"
cat <<EOF

Jalankan:   cd $APP_DIR && php artisan serve
Website:    $APP_URL
Admin:      $APP_URL/admin/login
Email:      $ADMIN_EMAIL
Kata sandi: $ADMIN_PASSWORD

Ingat: naikkan upload_max_filesize (8M) dan post_max_size (64M) di php.ini agar foto 4 MB bisa diunggah.
EOF
