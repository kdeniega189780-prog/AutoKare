#!/bin/sh
# Map Railway MySQL plugin variables to Laravel DB_* when unset.

if [ -n "${MYSQL_URL:-}" ] && [ -z "${DB_URL:-}" ]; then
  export DB_URL="$MYSQL_URL"
fi

if [ -n "${MYSQLHOST:-}" ] && [ -z "${DB_HOST:-}" ]; then
  export DB_HOST="$MYSQLHOST"
fi

if [ -n "${MYSQLPORT:-}" ] && [ -z "${DB_PORT:-}" ]; then
  export DB_PORT="$MYSQLPORT"
fi

if [ -n "${MYSQLUSER:-}" ] && [ -z "${DB_USERNAME:-}" ]; then
  export DB_USERNAME="$MYSQLUSER"
fi

if [ -n "${MYSQLPASSWORD:-}" ] && [ -z "${DB_PASSWORD:-}" ]; then
  export DB_PASSWORD="$MYSQLPASSWORD"
fi

if [ -n "${MYSQL_DATABASE:-}" ] && [ -z "${DB_DATABASE:-}" ]; then
  export DB_DATABASE="$MYSQL_DATABASE"
fi

if [ -z "${DB_CONNECTION:-}" ]; then
  if [ -n "${DB_URL:-}" ] || [ -n "${MYSQLHOST:-}" ] || [ -n "${MYSQL_URL:-}" ]; then
    export DB_CONNECTION="mysql"
  fi
fi
