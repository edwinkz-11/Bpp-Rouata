#!/usr/bin/env bash
# Menempelkan kode BPP Pertanian Routa ke proyek Laravel baru.
# Pemakaian: ./install.sh /path/ke/proyek-laravel
set -euo pipefail

TARGET="${1:-}"
HERE="$(cd "$(dirname "$0")" && pwd)"

if [ -z "$TARGET" ] || [ ! -f "$TARGET/artisan" ]; then
  echo "Pemakaian: ./install.sh /path/ke/proyek-laravel  (folder harus berisi file 'artisan')" >&2
  exit 1
fi

for dir in app config database lang public resources routes; do
  mkdir -p "$TARGET/$dir"
  cp -R "$HERE/$dir/." "$TARGET/$dir/"
done

echo "Selesai disalin ke: $TARGET"
echo
echo "Langkah berikutnya:"
echo "  1. Atur .env (database bpp_pertanian_routa, ADMIN_EMAIL, ADMIN_PASSWORD) - lihat README.md"
echo "  2. cd $TARGET"
echo "  3. php artisan key:generate"
echo "  4. php artisan migrate --seed"
echo "  5. php artisan storage:link"
echo "  6. php artisan serve"
