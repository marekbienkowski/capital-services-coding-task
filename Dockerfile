# Use an official PHP 8.3 runtime as a parent image
FROM php:8.3-fpm

# Set working directory
WORKDIR /var/www/symfony

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libicu-dev \
    libzip-dev \
    libpq-dev \
    zip \
    libonig-dev \
    curl \
    && docker-php-ext-install intl pdo pdo_mysql zip

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy application source code
COPY . .

# Install Symfony dependencies
RUN composer install --prefer-dist --no-scripts --no-interaction

# Set permissions for Symfony folders
RUN chown -R www-data:www-data var/cache var/log

# Expose port 9000 for PHP-FPM
EXPOSE 9000

# Start PHP-FPM server
CMD ["php-fpm"]
