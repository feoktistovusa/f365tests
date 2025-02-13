FROM php:8.4-fpm-alpine as dev

RUN apk add --no-cache \
    bash \
    curl \
    unzip \
    git \
    libpng-dev \
    oniguruma-dev \
    icu-dev \
    libzip-dev \
    mysql-client \
    supervisor \
    && docker-php-ext-install \
    pdo_mysql \
    intl \
    zip

WORKDIR /var/www/html

COPY --from=composer:2.8.5 /usr/bin/composer /usr/bin/composer

FROM dev as prod

COPY . .

RUN composer install --no-interaction --optimize-autoloader

RUN chown -R www-data:www-data /var/www/html

EXPOSE 9000

CMD ["php-fpm"]
