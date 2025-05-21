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

# Tạo thư mục cần thiết và phân quyền
RUN mkdir -p bootstrap/cache \
    && chmod -R 777 bootstrap/cache \
    && mkdir -p storage/framework/sessions storage/framework/views storage/framework/cache storage/logs \
    && chmod -R 777 storage

# Sao chép composer.json và composer.lock trước
COPY composer.json composer.lock ./

# Tạo file .env mới với cấu hình phù hợp cho quá trình build
RUN echo "APP_NAME=Laravel\n\
APP_ENV=production\n\
APP_KEY=base64:upk/1e0VXk+i9QWv6xnLqF+H/D4mRsSEsVtL1axUmAY=\n\
APP_DEBUG=true\n\
APP_URL=http://localhost\n\
DB_CONNECTION=mongodb\n\
MONGODB_URI=mongodb://localhost:27017/lms-backend\n\
DATABASE_NAME=lms-backend\n\
SESSION_DRIVER=file\n\
SESSION_LIFETIME=120\n\
CACHE_STORE=file\n\
QUEUE_CONNECTION=sync\n" > .env

# Cài đặt các phụ thuộc PHP nhưng bỏ qua các scripts post-install để tránh cache:clear
RUN composer install --optimize-autoloader --no-dev --no-scripts

# Sao chép phần còn lại của mã nguồn ứng dụng (sau khi cài đặt composer)
COPY . .

# Mở cổng
EXPOSE 8000

# Script khởi động
COPY docker-entrypoint.sh /docker-entrypoint.sh
RUN chmod +x /docker-entrypoint.sh

# Khởi động ứng dụng
ENTRYPOINT ["/docker-entrypoint.sh"]
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]