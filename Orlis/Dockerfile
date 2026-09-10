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

# Tối ưu Laravel (cache config, routes, views)
RUN php artisan config:clear \
    && php artisan optimize

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

CMD bash -c "\
    PORT=${PORT:-10000} && \
    sed -i \"s/Listen 80/Listen $PORT/g\" /etc/apache2/ports.conf && \
    sed -i \"s/:80>/:$PORT>/g\" /etc/apache2/sites-available/000-default.conf && \
    chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache && \
    php artisan migrate --force && \
    apache2-foreground"