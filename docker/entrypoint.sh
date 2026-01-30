#!/bin/bash
set -e

# Run migrations if enabled
if [ "${RUN_MIGRATIONS:-false}" = "true" ]; then
    echo "Running migrations..."
    php artisan migrate --force
fi

# Cache configuration and routes
echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Start Supervisor
echo "Starting Supervisor..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
