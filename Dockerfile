FROM php:8.4-cli

RUN apt-get update && apt-get install -y \
    unzip curl git libzip-dev zip sqlite3 libsqlite3-dev

RUN docker-php-ext-install pdo pdo_sqlite zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN php artisan key:generate --force || true
RUN mkdir -p database && touch database/database.sqlite
RUN php artisan migrate --force || true
RUN php artisan config:cache || true
RUN php artisan cache:clear || true
RUN chmod -R 755 storage bootstrap/cache || true

EXPOSE 10000

CMD php artisan serve --host=0.0.0.0 --port=${PORT:-10000}
