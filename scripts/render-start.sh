#!/usr/bin/env bash
set -e

export SESSION_DRIVER=file
export CACHE_STORE=file
export QUEUE_CONNECTION=sync

PORT="${PORT:-10000}"

cat > /etc/apache2/ports.conf <<EOF
Listen 0.0.0.0:${PORT}
EOF

cat > /etc/apache2/sites-available/000-default.conf <<EOF
<VirtualHost 0.0.0.0:${PORT}>
    ServerName _
    DocumentRoot /var/www/html/public

    <Directory /var/www/html/public>
        Options FollowSymLinks
        AllowOverride All
        Require all granted

        RewriteEngine On
        RewriteCond %{REQUEST_FILENAME} !-d
        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteRule ^ index.php [L]
    </Directory>

    ErrorLog /proc/self/fd/2
    CustomLog /proc/self/fd/1 combined
</VirtualHost>
EOF

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

rm -f bootstrap/cache/config.php

php artisan optimize:clear
#php artisan migrate --force
php artisan config:cache
php artisan view:cache

apache2ctl configtest

exec apache2-foreground