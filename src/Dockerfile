FROM php:8.4-cli

RUN apt-get update && apt-get install -y \
    $PHPIZE_DEPS \
    libzip-dev \
 && docker-php-ext-install pdo_mysql zip \
 && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www
