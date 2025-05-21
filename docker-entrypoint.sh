#!/bin/bash
set -e

# Đảm bảo các thư mục cần thiết tồn tại
mkdir -p bootstrap/cache
chmod -R 777 bootstrap/cache
mkdir -p storage/framework/sessions storage/framework/views storage/framework/cache storage/logs
chmod -R 777 storage

# Chạy các lệnh Laravel khi container khởi động
php artisan package:discover --ansi
php artisan config:clear
php artisan route:clear

# Kiểm tra kết nối MongoDB trước khi chạy các lệnh khác
echo "Kiểm tra kết nối MongoDB..."
if php -r "try { new MongoDB\Driver\Manager(getenv('MONGODB_URI')); echo 'Kết nối MongoDB thành công!'; } catch(Exception \$e) { echo 'Lỗi kết nối MongoDB: ' . \$e->getMessage(); exit(1); }"; then
    echo "Tiếp tục khởi động..."
    php artisan cache:clear
else
    echo "Không thể kết nối MongoDB, nhưng vẫn tiếp tục khởi động..."
fi

# Truyền tham số cmd đến script
exec "$@"