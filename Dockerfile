FROM php:8.4-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    curl \
    libzip-dev \
    zip \
    nodejs \
    npm

# Install PHP extensions (including SQLite)
RUN docker-php-ext-install pdo pdo_mysql pdo_sqlite zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy project
COPY . .

# Install Node dependencies
RUN npm install --legacy-peer-deps

# Build frontend assets
RUN npm run build

# Install Laravel dependencies
RUN composer install --optimize-autoloader --no-dev

# Create startup script
RUN echo '#!/bin/bash\n\
php artisan migrate --force\n\
php artisan config:cache\n\
php artisan route:cache\n\
php artisan view:cache\n\
php artisan serve --host=0.0.0.0 --port=10000' > /app/startup.sh && chmod +x /app/startup.sh

# Expose port
EXPOSE 10000

# Start server with migrations
CMD ["/app/startup.sh"]
