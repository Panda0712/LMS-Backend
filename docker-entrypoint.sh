#!/bin/bash
set -e

# Cập nhật file .env từ biến môi trường Railway
# Railway tự động đưa biến môi trường vào container, không cần .env
# Nhưng Laravel vẫn ưu tiên đọc từ file .env, nên ta cập nhật file này
env | grep -E "^(APP_|DB_|MONGODB_|SESSION_|CACHE_|QUEUE_)" > /tmp/env_vars
while IFS= read -r line; do
  key=$(echo $line | cut -d= -f1)
  value=$(echo $line | cut -d= -f2-)
  # Đưa giá trị vào file .env
  grep -q "^$key=" .env && sed -i "s|^$key=.*|$key=$value|" .env || echo "$key=$value" >> .env
done < /tmp/env_vars
rm /tmp/env_vars

# Đảm bảo các thư mục cần thiết tồn tại
mkdir -p bootstrap/cache
chmod -R 777 bootstrap/cache
mkdir -p storage/framework/sessions storage/framework/views storage/framework/cache storage/logs
chmod -R 777 storage

# Chạy các lệnh Laravel khi container khởi động
php artisan package:discover --ansi
php artisan config:clear
php artisan route:clear

# Xử lý URL của MongoDB để chuyển sang định dạng URI đúng
# Railway chuyển MONGODB_URI thành MONGODB_URI="mongodb+srv://user:pass@host"
# MongoDB chỉ chấp nhận không có dấu ngoặc kép ở đầu và cuối
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

echo "PORT được đặt thành: $PORT"

# Truyền tham số cmd đến script
exec "$@"