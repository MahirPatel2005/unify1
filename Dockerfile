FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libzip-dev libicu-dev libonig-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd mysqli pdo pdo_mysql intl mbstring zip \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

RUN echo "upload_max_filesize = 10M\npost_max_size = 10M" > /usr/local/etc/php/conf.d/uploads.ini

WORKDIR /var/www/html
COPY . /var/www/html/

RUN mkdir -p /var/www/html/writable/cache \
             /var/www/html/writable/logs \
             /var/www/html/writable/session \
             /var/www/html/writable/debugbar \
             /var/www/html/writable/uploads \
             /var/www/html/files \
             /var/www/html/plugins \
    && chown -R www-data:www-data /var/www/html/writable /var/www/html/files /var/www/html/plugins /var/www/html/app/Config/activated_plugins.json \
    && chmod -R 777 /var/www/html/writable /var/www/html/files /var/www/html/plugins \
    && chmod 777 /var/www/html/app/Config/activated_plugins.json

COPY docker/start-apache.sh /usr/local/bin/start-apache.sh
RUN chmod +x /usr/local/bin/start-apache.sh

EXPOSE 10000
CMD ["/usr/local/bin/start-apache.sh"]
