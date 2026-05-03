FROM php:8.3-cli

RUN apt-get update && apt-get install -y \
    unzip curl git libzip-dev zip sqlite3 libsqlite3-dev

RUN docker-php-ext-install pdo pdo_sqlite zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN php artisan key:generate --force || true
RUN php artisan config:clear || true
RUN php artisan cache:clear || true

EXPOSE 10000

CMD php artisan serve --host=0.0.0.0 --port=10000
