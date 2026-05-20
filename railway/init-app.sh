#!/bin/sh
set -e

. "$(dirname "$0")/env-mysql.sh"

php artisan package:discover --ansi
# Ensure cached config doesn't pin DB_CONNECTION=sqlite in production.
php artisan optimize:clear
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
