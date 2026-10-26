#!/bin/bash
set -e

if [ "${RUN_MIGRATIONS:-false}" = "true" ]; then
    echo "Running migrations..."
    php artisan migrate --force
fi

echo "Caching configuration..."
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan event:cache
php artisan filament:assets
php artisan filament:cache

# Hand off to the image's own entrypoint (starts nginx + FPM)
exec docker-php-serversideup-entrypoint "$@"
