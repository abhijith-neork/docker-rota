# Use the official PHP image as the base image
FROM php:7.2.24-apache as php

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libzip-dev \
    zip \
    unzip

# Install the MySQLi extension
RUN docker-php-ext-install mysqli

# Enable the MySQLi extension
RUN docker-php-ext-enable mysqli

# Install any other PHP extensions if needed
RUN docker-php-ext-install pdo pdo_mysql

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

COPY --from=composer:2.7.2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-scripts --no-progress --no-interaction

COPY codeigniter.conf /etc/apache2/sites-available/

RUN a2ensite codeigniter.conf \
    && service apache2 restart || true


RUN cd /etc/apache2/sites-available \
    && a2dissite 000-default.conf \
    && service apache2 restart || true

# 3. mod_rewrite for URL rewrite and mod_headers for .htaccess extra headers like Access-Control-Allow-Origin-
RUN a2enmod rewrite headers

# 4. start with base php config, then add extensions
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

ARG uid

EXPOSE 80
