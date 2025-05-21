#!/bin/bash
set -e

# Đảm bảo các thư mục cần thiết tồn tại
mkdir -p bootstrap/cache
chmod -R 777 bootstrap/cache
mkdir -p storage/framework/sessions storage/framework/views storage/framework/cache storage/logs
chmod -R 777 storage

# Tạo một .env tối thiểu mà không cố gắng đọc từ biến môi trường
cat > .env << EOL
APP_NAME=Laravel
APP_ENV=production
APP_KEY=base64:upk/1e0VXk+i9QWv6xnLqF+H/D4mRsSEsVtL1axUmAY=
APP_DEBUG=true
APP_URL=${APP_URL:-http://localhost}
DB_CONNECTION=mongodb
MONGODB_URI=${MONGODB_URI:-mongodb://localhost:27017/lms-backend}
DATABASE_NAME=${DATABASE_NAME:-lms-backend}
SESSION_DRIVER=file
SESSION_LIFETIME=120
CACHE_STORE=file
QUEUE_CONNECTION=sync
SANCTUM_STATEFUL_DOMAINS=${SANCTUM_STATEFUL_DOMAINS:-localhost:8080}
EOL

# Tăng memory limit cho PHP
echo "memory_limit = 256M" > /usr/local/etc/php/conf.d/memory-limit.ini

# Chạy các lệnh Laravel khi container khởi động
php artisan package:discover --ansi
php artisan config:clear
php artisan route:clear

# Xử lý URL của MongoDB để chuyển sang định dạng URI đúng
if [ ! -z "$MONGODB_URI" ]; then
  # Loại bỏ dấu ngoặc kép ở đầu và cuối nếu có
  CLEAN_URI=$(echo $MONGODB_URI | sed 's/^"//;s/"$//')
  export MONGODB_URI=$CLEAN_URI
  echo "MONGODB_URI đã được điều chỉnh."
fi

# Kiểm tra kết nối MongoDB trước khi chạy các lệnh khác
echo "Kiểm tra kết nối MongoDB..."
if php -r "try { new MongoDB\Driver\Manager(getenv('MONGODB_URI')); echo \"Kết nối MongoDB thành công!\"; } catch(\Exception \$e) { echo \"Lỗi kết nối MongoDB: \" . \$e->getMessage(); }"; then
  echo "Tiếp tục khởi động..."
  php artisan cache:clear
else
  echo "Cảnh báo: Không thể kết nối MongoDB, nhưng vẫn tiếp tục khởi động..."
fi

# Lấy PORT từ biến môi trường hoặc mặc định 8080
PORT=${PORT:-8080}
echo "PORT được đặt thành: $PORT"

# Ghi đè CMD để đảm bảo port được truyền đúng
if [ "$1" = "php" ] && [ "$2" = "artisan" ] && [ "$3" = "serve" ]; then
  exec php artisan serve --host=0.0.0.0 --port=$PORT
else
  exec "$@"
fi