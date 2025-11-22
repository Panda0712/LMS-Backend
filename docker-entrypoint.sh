#!/bin/bash
set -e

echo "Starting Container..."

# Create required directories
mkdir -p storage/framework/sessions storage/framework/views storage/framework/cache storage/logs
mkdir -p bootstrap/cache
chmod -R 777 storage bootstrap/cache

# Generate .env dynamically if missing
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

# Generate key if missing
if [ -z "$APP_KEY" ]; then
    echo "Generating APP_KEY..."
    php artisan key:generate --force
fi

# Clean caches
php artisan config:clear
php artisan route:clear
php artisan cache:clear

# Fix MongoDB URI if quoted
if echo "$MONGODB_URI" | grep -q '^"'; then
    export MONGODB_URI=$(echo $MONGODB_URI | sed 's/^"//;s/"$//')
fi

echo "Checking MongoDB connection..."
php -r "try { new MongoDB\Driver\Manager(getenv('MONGODB_URI')); echo \"MongoDB connected OK\n\"; } catch(Exception \$e) { echo \"MongoDB connection error: \".$e->getMessage().\"\n\"; }";

# PORT override from Railway
PORT=${PORT:-8080}
echo "Listening on port: $PORT"

exec php artisan serve --host=0.0.0.0 --port=$PORT
