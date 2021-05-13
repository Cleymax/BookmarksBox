FROM php:7.2-apache

RUN apt-get update \
    && apt-get install -y --no-install-recommends git zlib1g-dev libzip-dev zip unzip postgresql-dev

RUN docker-php-ext-install pdo pdo_pgsql zip
