FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libsqlite3-dev \
    sqlite3 \
    curl \
    nodejs \
    npm \
    && docker-php-ext-install pdo_sqlite mbstring bcmath

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction

RUN npm install && npm run build

RUN mkdir -p database \
    && touch database/database.sqlite

RUN mkdir -p storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache database

RUN php artisan config:clear

EXPOSE 10000

CMD php artisan migrate --seed --force && \
    php artisan storage:link && \
    php artisan serve --host=0.0.0.0 --port=${PORT:-10000}