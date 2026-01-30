# Stage 1: Build assets
FROM node:20-alpine AS assets-builder
WORKDIR /app

# Install PHP and Composer to get vendor assets needed for the build
RUN apk add --no-cache php83 php83-common php83-iconv php83-json php83-gd php83-curl php83-xml php83-mysqli php83-imap php83-cgi php83-pdo php83-pdo_mysql php83-soap php83-posix php83-gettext php83-ldap php83-ctype php83-dom php83-simplexml php83-tokenizer php83-xmlwriter php83-zip php83-mbstring php83-bcmath php83-phar php83-openssl curl composer

COPY composer.* ./
RUN composer install --no-interaction --no-dev --no-scripts --no-autoloader

COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# Stage 2: Application
FROM php:8.4-fpm-bullseye

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nginx \
    supervisor \
    libzip-dev \
    libicu-dev

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip intl opcache

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy existing application directory contents
COPY . .

# Copy built assets from Stage 1
COPY --from=assets-builder /app/public/build ./public/build

# Install dependencies
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Setup configurations
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/php.ini /usr/local/etc/php/conf.d/app-php.ini
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh

# Set permissions
RUN chmod +x /usr/local/bin/entrypoint.sh \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port 80
EXPOSE 80

# Entrypoint
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
