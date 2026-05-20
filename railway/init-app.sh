#!/bin/sh
set -e

# Railway's MySQL plugin typically injects MYSQL_URL. Laravel expects DB_CONNECTION/DB_URL.
# If DB_URL isn't set, map it from MYSQL_URL to avoid falling back to SQLite.
if [ -n "${MYSQL_URL:-}" ] && [ -z "${DB_URL:-}" ]; then
  export DB_URL="$MYSQL_URL"
fi

if [ -n "${DB_URL:-}" ] && [ -z "${DB_CONNECTION:-}" ]; then
  export DB_CONNECTION="mysql"
fi

php artisan package:discover --ansi
# Ensure cached config doesn't pin DB_CONNECTION=sqlite in production.
php artisan optimize:clear
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
