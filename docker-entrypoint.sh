#!/bin/bash
set -e

echo "Starting Container..."

# Ensure folders exist
mkdir -p storage/framework/sessions storage/framework/views storage/framework/cache storage/logs
mkdir -p bootstrap/cache
chmod -R 777 storage bootstrap/cache

# Create .env if missing (Railway env will override anyway)
if [ ! -f .env ]; then
cat > .env <<EOL
APP_NAME=Laravel
APP_ENV=production
APP_KEY=${APP_KEY}
APP_DEBUG=false
APP_URL=${APP_URL}

DB_CONNECTION=mongodb
MONGODB_URI=${MONGODB_URI}
DATABASE_NAME=${DATABASE_NAME}

SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync

SANCTUM_STATEFUL_DOMAINS=${SANCTUM_STATEFUL_DOMAINS}
JWT_SECRET=${JWT_SECRET}
EOL
    echo ".env created!"
fi

# Generate key if none provided
if [ -z "$APP_KEY" ]; then
    echo "Generating APP_KEY..."
    php artisan key:generate --force
fi

# Clear caches
php artisan config:clear
php artisan route:clear
php artisan cache:clear

# Fix Mongo URI if it contains quotes
if echo "$MONGODB_URI" | grep -q '^"'; then
    export MONGODB_URI=$(echo $MONGODB_URI | sed 's/^"//;s/"$//')
fi

# -----------------------------------------------
# SAFE MONGODB CHECK (NO INLINE PHP)
# -----------------------------------------------
echo "Checking MongoDB connection..."

cat > /tmp/check-mongo.php <<'PHP'
<?php
require 'vendor/autoload.php';

$uri = getenv('MONGODB_URI');

try {
    $manager = new MongoDB\Driver\Manager($uri);
    $manager->executeCommand("admin", new MongoDB\Driver\Command(['ping' => 1]));
    echo "MongoDB connected OK\n";
} catch (Exception $e) {
    echo "MongoDB connection error: " . $e->getMessage() . "\n";
}
PHP

php /tmp/check-mongo.php
# Không dừng container nếu MongoDB chưa sẵn sàng (Railway auto-restart)

# -----------------------------------------------
# Start Laravel built-in server
# -----------------------------------------------
PORT=${PORT:-8080}
echo "Listening on port: $PORT"

exec php artisan serve --host=0.0.0.0 --port=$PORT
