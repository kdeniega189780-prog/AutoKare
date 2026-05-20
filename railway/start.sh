#!/bin/sh
set -e

. "$(dirname "$0")/env-mysql.sh"

# Railway injects PORT; default matches local artisan serve.
exec php artisan serve --host=0.0.0.0 --port="${PORT:-8080}"
