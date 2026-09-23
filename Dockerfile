# Use the official PHP 8.1 FPM image as the base
FROM php:8.1-fpm

# Set the working directory inside the container
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    nano \
    net-tools \
    lsof \
    procps \
    openssl \
    libfcgi0ldbl \
    libpq-dev \
    libzip-dev \
    && docker-php-ext-install pdo pdo_mysql zip

RUN docker-php-ext-install pdo pdo_mysql

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy project files into the container
COPY . .

# Set permissions for cache and log directories
RUN chmod -R 777 /var/www/html/var/cache /var/www/html/var/log
RUN chmod -R 755 /var/www/html/public

# Install Symfony dependencies
RUN composer install --no-dev --optimize-autoloader

# Set PHP configuration values
RUN echo "upload_max_filesize=10M" > /usr/local/etc/php/conf.d/uploads.ini && \
    echo "post_max_size=12M" >> /usr/local/etc/php/conf.d/uploads.ini

# PHP socket
RUN mkdir -p /var/run/php && \
    echo "listen = /var/run/php/php-fpm.sock" > /usr/local/etc/php-fpm.d/socket.conf && \
    echo "listen.owner = www-data" >> /usr/local/etc/php-fpm.d/socket.conf && \
    echo "listen.group = www-data" >> /usr/local/etc/php-fpm.d/socket.conf && \
    echo "listen.mode = 0666" >> /usr/local/etc/php-fpm.d/socket.conf

# Expose PHP-FPM port
EXPOSE 9000
