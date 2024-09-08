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
    autoconf \
    build-essential \
    && docker-php-ext-install intl pdo pdo_mysql zip

# Install Xdebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy application source code
COPY . .

# Install Symfony dependencies
RUN composer install --prefer-dist --no-scripts --no-interaction

# Configure Xdebug
RUN echo "zend_extension=xdebug.so" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_port=9003" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.idekey=PHPSTORM" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Expose port 9000 for PHP-FPM and 9003 for Xdebug
EXPOSE 9000 9003

# Start PHP-FPM server
CMD ["php-fpm"]
