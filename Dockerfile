FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libzip-dev libicu-dev libonig-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd mysqli pdo pdo_mysql intl mbstring zip \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html
COPY . /var/www/html/

RUN mkdir -p /var/www/html/writable/cache \
             /var/www/html/writable/logs \
             /var/www/html/writable/session \
             /var/www/html/writable/debugbar \
             /var/www/html/writable/uploads \
             /var/www/html/files \
    && chown -R www-data:www-data /var/www/html/writable /var/www/html/files \
    && chmod -R 777 /var/www/html/writable /var/www/html/files

COPY docker/start-apache.sh /usr/local/bin/start-apache.sh
RUN chmod +x /usr/local/bin/start-apache.sh

EXPOSE 10000
CMD ["/usr/local/bin/start-apache.sh"]
