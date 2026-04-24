# Stage 1: Build assets
FROM node:20-alpine AS assets-builder
WORKDIR /app

# Install minimal PHP and Composer just to fetch vendor dependencies
RUN apk add --no-cache php84 php84-phar php84-mbstring php84-openssl php84-tokenizer php84-ctype php84-dom php84-xml php84-xmlwriter php84-simplexml php84-zip curl composer

COPY composer.* ./
RUN composer install --no-interaction --no-dev --no-scripts --no-autoloader --ignore-platform-reqs

COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# Stage 2: Application
FROM php:8.4-fpm-bullseye

WORKDIR /var/www/html

# Install system dependencies, build PHP extensions, and clean up in one layer
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git \
        curl \
        zip \
        unzip \
        nginx \
        supervisor \
        mariadb-client \
        libpng-dev \
        libonig-dev \
        libxml2-dev \
        libzip-dev \
        libicu-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip intl opcache \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apt-get purge -y libpng-dev libonig-dev libxml2-dev libzip-dev libicu-dev \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/pear

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy composer files first for better layer caching
COPY composer.json composer.lock ./
RUN composer install --no-interaction --no-dev --no-scripts --no-autoloader \
    && rm -rf /root/.composer/cache

# Copy application code
COPY . .
COPY --from=assets-builder /app/public/build ./public/build

# Generate optimized autoloader
RUN composer dump-autoload --optimize --no-scripts

# Setup configurations
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/php.ini /usr/local/etc/php/conf.d/app-php.ini
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh

RUN chmod +x /usr/local/bin/entrypoint.sh \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
