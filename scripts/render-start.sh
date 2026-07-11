#!/usr/bin/env bash
set -e

PORT="${PORT:-10000}"

sed -ri "s/Listen 80/Listen ${PORT}/g; s/Listen 10000/Listen ${PORT}/g" /etc/apache2/ports.conf
sed -ri "s/<VirtualHost \*:[0-9]+>/<VirtualHost *:${PORT}>/g" /etc/apache2/sites-available/000-default.conf

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

php artisan config:clear
php artisan route:clear
php artisan view:clear

php artisan migrate --force

php artisan config:cache
php artisan view:cache

exec apache2-foreground
