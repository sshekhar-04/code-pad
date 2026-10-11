#!/bin/bash
set -e

# If Render or cloud environment sets a custom PORT, configure Apache to listen on it
if [ -n "$PORT" ]; then
    sed -ri "s/Listen [0-9]+/Listen $PORT/g" /etc/apache2/ports.conf
    sed -ri "s/<VirtualHost \*:[0-9]+>/<VirtualHost \*:$PORT>/g" /etc/apache2/sites-available/*.conf
fi

# Ensure storage directories exist with proper permissions
mkdir -p /var/www/html/storage/framework/cache
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/bootstrap/cache
rm -f /var/www/html/bootstrap/cache/compiled.php

chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Ensure .env exists if not provided
if [ ! -f /var/www/html/.env ]; then
    if [ -f /var/www/html/.env.example ]; then
        cp /var/www/html/.env.example /var/www/html/.env
    fi
fi

# Generate application key if not set
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "SomeRandomString" ]; then
    php artisan key:generate --force || true
fi

# Automatically run database migrations on container start
echo "Running database migrations..."
php artisan migrate --force || true

# Automatically run database seeder on container start
echo "Running database seeder..."
php artisan db:seed --force || true

# Clear cache to guarantee latest routes, views, config
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

exec "$@"
