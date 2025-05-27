# Dockerfile
FROM php:8.2-cli

# Instalacja zależności systemowych i PHP
RUN apt-get update \
    && apt-get install -y git unzip libicu-dev libzip-dev zip \
    && docker-php-ext-install intl zip pdo pdo_mysql

# Instalacja Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Ustaw katalog roboczy
WORKDIR /app

# Instalacja Symfony CLI
RUN curl -sS https://get.symfony.com/cli/installer | bash \
    && mv /root/.symfony*/bin/symfony /usr/local/bin/symfony

# Kopiowanie aplikacji
COPY . .

# Instalacja zależności aplikacji
RUN composer install --no-dev --optimize-autoloader

# Uruchomienie aplikacji (jeśli potrzebujesz lokalnie)
CMD ["php", "-S", "0.0.0.0:3000", "-t", "public"]
