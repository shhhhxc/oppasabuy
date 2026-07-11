#!/usr/bin/env bash
set -e

export SESSION_DRIVER=file
export CACHE_STORE=file
export QUEUE_CONNECTION=sync

PORT="${PORT:-10000}"

cd /var/www/html

mkdir -p \
    storage/app/public \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

chown -R www-data:www-data storage bootstrap/cache
chmod -R ug+rwX storage bootstrap/cache

if [ ! -L public/storage ]; then
    php artisan storage:link || true
fi

rm -f bootstrap/cache/config.php

php artisan optimize:clear
php artisan config:cache
php artisan view:cache

echo "Starting Oppasabuy on 0.0.0.0:${PORT}"

exec php artisan serve \
    --host=0.0.0.0 \
    --port="${PORT}"