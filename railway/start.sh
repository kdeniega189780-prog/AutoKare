#!/bin/sh
set -e

# Ensure runtime has correct DB env (config cache reads env at cache-build time).
if [ -n "${MYSQL_URL:-}" ] && [ -z "${DB_URL:-}" ]; then
  export DB_URL="$MYSQL_URL"
fi

if [ -n "${DB_URL:-}" ] && [ -z "${DB_CONNECTION:-}" ]; then
  export DB_CONNECTION="mysql"
fi

# Railway injects PORT; default matches local artisan serve.
exec php artisan serve --host=0.0.0.0 --port="${PORT:-8080}"
