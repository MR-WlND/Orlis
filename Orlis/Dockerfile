FROM php:8.3-apache

WORKDIR /var/www/html

# Cài đặt các extension PHP cần thiết
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libsqlite3-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo_mysql \
        pdo_sqlite \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
        opcache \
    && rm -rf /var/lib/apt/lists/*

# Bật mod_rewrite cho Apache (cần thiết cho Laravel)
RUN a2enmod rewrite

# Cài đặt Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Cài đặt Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy toàn bộ code vào container
COPY . .

# Cài đặt các thư viện PHP (production only)
RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction

# Build assets frontend
RUN npm install && npm run build

# Xóa cache cũ (không cache tại build vì env vars chỉ có ở runtime trên Render)
RUN php artisan config:clear \
    && php artisan route:clear \
    && php artisan view:clear

# Phân quyền thư mục
RUN touch database/database.sqlite \
    && chmod -R 775 storage bootstrap/cache database/database.sqlite \
    && chown -R www-data:www-data storage bootstrap/cache database/database.sqlite

# Cấu hình Apache trỏ vào thư mục public của Laravel
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' /etc/apache2/sites-available/000-default.conf \
    && echo '<Directory /var/www/html/public>\n\tAllowOverride All\n\tRequire all granted\n</Directory>' >> /etc/apache2/sites-available/000-default.conf

# Bật mod_rewrite và headers
RUN a2enmod rewrite headers

# Cấu hình OPcache để tăng tốc PHP
RUN echo "opcache.enable=1\n\
opcache.memory_consumption=128\n\
opcache.interned_strings_buffer=8\n\
opcache.max_accelerated_files=10000\n\
opcache.revalidate_freq=0\n\
opcache.validate_timestamps=0\n\
opcache.fast_shutdown=1" >> /usr/local/etc/php/conf.d/opcache.ini

# Render dùng biến PORT động (thường là 10000), Apache phải lắng nghe đúng cổng đó
EXPOSE 10000

# Tạo script khởi động để dễ dàng theo dõi log và tránh lỗi timeout
RUN echo '#!/bin/bash\n\
set -e\n\
export PORT=${PORT:-10000}\n\
echo "=> Configuring Apache for PORT $PORT..."\n\
echo "Listen $PORT" > /etc/apache2/ports.conf\n\
sed -i "s/<VirtualHost \*:.*>/<VirtualHost \*:$PORT>/g" /etc/apache2/sites-available/000-default.conf\n\
echo "ServerName localhost" >> /etc/apache2/apache2.conf\n\
echo "=> Setting permissions..."\n\
mkdir -p /var/www/html/storage/framework/sessions\n\
mkdir -p /var/www/html/storage/framework/views\n\
mkdir -p /var/www/html/storage/framework/cache/data\n\
mkdir -p /var/www/html/storage/logs\n\
echo "=> Caching config..."\n\
php artisan config:cache\n\
echo "=> Caching routes..."\n\
php artisan route:cache || echo "Route cache failed, skipping..."\n\
echo "=> Caching views..."\n\
php artisan view:cache\n\
echo "=> Running migrations..."\n\
php artisan migrate --force || echo "Migration failed, skipping..."\n\
echo "=> Creating storage link..."\n\
php artisan storage:link || true\n\
echo "=> Fixing ownership..."\n\
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database /var/www/html/public/storage\n\
echo "=> Starting Apache..."\n\
exec apache2-foreground\n\
' > /usr/local/bin/start.sh && chmod +x /usr/local/bin/start.sh

CMD ["/usr/local/bin/start.sh"]