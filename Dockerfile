FROM php:8.2-apache

# Install dependencies
RUN apt-get update && apt-get install -y \
mariadb-client \
zip unzip \
git \
libmagickwand-dev --no-install-recommends \
&& pecl install imagick \
&& docker-php-ext-enable imagick \
&& docker-php-ext-install pdo_mysql 

# Copy the application source code to the container
COPY src/ /var/www/html/

# Set the correct permissions for the web directory
RUN chown -R www-data:www-data /var/www/html

# Install Composer
RUN apt-get update && apt-get -y --no-install-recommends install git \
    && php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer \
    && rm -rf /var/lib/apt/lists/*
