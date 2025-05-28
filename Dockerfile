FROM php:8.2-fpm

# Instalacja zależności systemowych
RUN apt-get update && apt-get install -y \
    git unzip libicu-dev libzip-dev zlib1g-dev libpng-dev libjpeg-dev libonig-dev libpq-dev \
    && docker-php-ext-install intl pdo pdo_mysql opcache zip

# Instalacja Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Ustawienie katalogu roboczego
WORKDIR /app
COPY . .

# Instalacja zależności PHP
ENV SYMFONY_SKIP_ENV_CHECK=1
RUN composer install --no-scripts --no-interaction --prefer-dist
# Eksponuj port dla Railway
EXPOSE 8000

# Start symfony server
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]