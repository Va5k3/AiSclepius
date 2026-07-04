FROM composer:2 AS vendor

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader --no-scripts --ignore-platform-reqs

COPY . .

FROM php:8.3-apache

RUN apt-get update \
    && apt-get install -y --no-install-recommends libicu-dev libonig-dev libzip-dev unzip \
    && docker-php-ext-install pdo_mysql intl mbstring zip opcache \
    && a2enmod rewrite headers \
    && rm -rf /var/lib/apt/lists/*

RUN printf '%s\n' \
    '<VirtualHost *:80>' \
    '    DocumentRoot /var/www/html/public' \
    '    <Directory /var/www/html/public>' \
    '        AllowOverride All' \
    '        Require all granted' \
    '    </Directory>' \
    '</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html

COPY --from=vendor /app /var/www/html

RUN mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache

RUN chown -R www-data:www-data storage bootstrap/cache

EXPOSE 80

CMD ["apache2-foreground"]