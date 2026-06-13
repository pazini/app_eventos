#!/usr/bin/env bash
set -euo pipefail

cd /var/www/html

mkdir -p \
    storage/app/public \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/testing \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

if [[ ! -L public/storage || ! -e public/storage ]]; then
    rm -rf public/storage
    php artisan storage:link
fi

chown -R www-data:www-data storage bootstrap/cache

: > /etc/cron.env
while IFS='=' read -r name value; do
    printf 'export %s=%q\n' "$name" "$value" >> /etc/cron.env
done < <(env)
chown root:www-data /etc/cron.env
chmod 0640 /etc/cron.env

php artisan optimize:clear

if [[ "${RUN_MIGRATIONS:-false}" == "true" ]]; then
    php artisan migrate --force
fi

exec "$@"
