FROM php:8.1.1-fpm

RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    && docker-php-ext-install pdo pdo_mysql

RUN git config --global --add safe.directory /var/www \
    && git config --global --add safe.directory /usr/src/app

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
