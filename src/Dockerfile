FROM php:8.4-cli

RUN apt-get update && apt-get install -y \
    $PHPIZE_DEPS \
    libzip-dev \
    unzip \
    git \
 && docker-php-ext-install pdo_mysql zip \
 && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www