FROM php:8.4-cli-bookworm

RUN apt-get update && apt-get install -y \
    unzip git \
    libzip-dev \
    libonig-dev \
    libcurl4-openssl-dev \
    libsqlite3-dev \
    libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_sqlite mbstring bcmath zip curl

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction

RUN mkdir -p storage/framework/{sessions,views,cache} storage/logs bootstrap/cache \
    && chmod -R a+rw storage bootstrap/cache

EXPOSE 8080
CMD php artisan migrate --force && php artisan serve --host 0.0.0.0 --port ${PORT:-8080}
