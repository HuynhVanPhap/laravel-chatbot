# Set master image
FROM php:7.4-fpm

# Set working directory
WORKDIR /var/www/html

# Install Additional dependencies
# RUN apt-get update && apt-get -y upgrade apt-get install -y \
#     build-base shadow supervisor \
#     php7-common \
#     php7-pdo \
#     php7-pdo_mysql \
#     php7-mysqli \
#     php7-mcrypt \
#     php7-mbstring \
#     php7-xml \
#     php7-openssl \
#     php7-json \
#     php7-phar \
#     php7-zip \
#     php7-gd \
#     php7-dom \
#     php7-session \
#     php7-zlib
RUN apt-get update && apt-get install -y libfreetype6-dev libjpeg62-turbo-dev libpng-dev
RUN apt-get install -y libzip-dev zip
RUN docker-php-ext-install zip
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install -j$(nproc) gd

# Add and Enable PHP-PDO Extenstions for PHP connect Mysql
RUN docker-php-ext-install pdo pdo_mysql
RUN docker-php-ext-enable pdo_mysql

# This extension required for Laravel Horizon
RUN docker-php-ext-install pcntl

# Install Curl
RUN apt-get update && apt-get upgrade -y
RUN apt-get install -y curl
# Install NodeJS and Npm
RUN curl -fsSL https://deb.nodesource.com/setup_19.x | bash
RUN apt-get install -y nodejs

# Remove Cache
RUN rm -rf /var/cache/apk/*

# Use the default production configuration for PHP-FPM ($PHP_INI_DIR variable already set by the default image)
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Setup Composer
RUN curl -sS https://getcomposer.org/installer | php
RUN chmod a+x composer.phar
RUN mv composer.phar /usr/local/bin/composer

# Add UID '1000' to www-data
RUN usermod -u 1000 www-data

# Copy existing application directory
COPY . .

# Chang app directory permission
RUN chown -R :root .

# Set Timezone To Saigon
RUN rm -rf /etc/localtime
RUN ln -s /usr/share/zoneinfo/Asia/Saigon /etc/localtime
