FROM php:8.2-cli

WORKDIR /app

# Cài đặt các gói phụ thuộc
RUN apt-get update && apt-get install -y \
    libssl-dev \
    libzip-dev \
    libcurl4-openssl-dev \
    pkg-config \
    libonig-dev \
    nodejs \
    npm \
    git \
    unzip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Cài đặt các extension PHP cần thiết
RUN docker-php-ext-install zip pdo pdo_mysql mbstring

# Cài đặt MongoDB extension
RUN pecl install mongodb && docker-php-ext-enable mongodb

# Cài đặt Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Sao chép mã nguồn ứng dụng
COPY . /app

# Cài đặt các phụ thuộc PHP
RUN composer install --optimize-autoloader --no-dev

# Tạo thư mục cần thiết và phân quyền
RUN mkdir -p bootstrap/cache \
    && chmod -R 777 bootstrap/cache \
    && mkdir -p storage/framework/sessions storage/framework/views storage/framework/cache storage/logs \
    && chmod -R 777 storage

# Khám phá các package
RUN php artisan package:discover

# Cấu hình ứng dụng
RUN php artisan config:clear \
    && php artisan cache:clear \
    && php artisan route:clear

# Mở cổng
EXPOSE 8000

# Khởi động ứng dụng
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=${PORT:-8000}"]