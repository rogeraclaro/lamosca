FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Configure and install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    mysqli \
    gd \
    zip

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Configure Apache to allow .htaccess
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html

# PHP configuration for development
RUN echo "error_reporting = E_ALL" >> /usr/local/etc/php/conf.d/docker-php-errors.ini \
    && echo "display_errors = On" >> /usr/local/etc/php/conf.d/docker-php-errors.ini \
    && echo "display_startup_errors = On" >> /usr/local/etc/php/conf.d/docker-php-errors.ini \
    && echo "log_errors = On" >> /usr/local/etc/php/conf.d/docker-php-errors.ini

EXPOSE 80
