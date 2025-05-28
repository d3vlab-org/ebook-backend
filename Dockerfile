FROM php:8.2-fpm

# Instalacja zależności
RUN apt-get update && apt-get install -y \
    git unzip libicu-dev libzip-dev zlib1g-dev libpng-dev libjpeg-dev libonig-dev libpq-dev \
    && docker-php-ext-install intl pdo pdo_mysql zip opcache

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

RUN composer install --no-interaction --prefer-dist --optimize-autoloader

CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]