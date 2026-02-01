#!/bin/sh
set -e

# -----------------------------
# Fix permissions (once per container start)
# -----------------------------
chown -R $(id -u):$(id -g) storage bootstrap/cache || true
chmod -R 775 storage bootstrap/cache || true

# -----------------------------
# Install composer dependencies if missing
# -----------------------------
if [ ! -d vendor ]; then
    echo "📦 Installing composer dependencies..."
    composer install --no-interaction --optimize-autoloader
fi

# -----------------------------
# Generate Laravel app key if missing
# -----------------------------
if [ ! -f .env ]; then
    echo "🔑 Generating app key..."
    php artisan key:generate
fi

# -----------------------------
# Run migrations (optional, safe for dev)
# -----------------------------
echo "🗄 Running migrations..."
php artisan migrate --force || true

# -----------------------------
# Execute the CMD (php-fpm)
# -----------------------------
exec "$@"
