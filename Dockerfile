FROM php:8.2-cli

WORKDIR /app

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libssl-dev \
    libzip-dev \
    libcurl4-openssl-dev \
    pkg-config \
    libonig-dev \
    git \
    unzip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install zip pdo pdo_mysql mbstring

# Install MongoDB extension
RUN pecl install mongodb && docker-php-ext-enable mongodb

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy composer files
COPY composer.json composer.lock ./

# Install dependencies without running scripts
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Copy all source code
COPY . .

# Create storage and cache directories
RUN mkdir -p storage bootstrap/cache \
    && chmod -R 777 storage bootstrap/cache

# Expose port
EXPOSE 8080

# Entry script
COPY docker-entrypoint.sh /docker-entrypoint.sh
RUN chmod +x /docker-entrypoint.sh

ENTRYPOINT ["/docker-entrypoint.sh"]

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]
