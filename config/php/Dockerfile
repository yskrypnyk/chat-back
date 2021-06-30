FROM php:7.4-fpm

# Use the default production configuration
# RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Copy composer.lock and composer.json
COPY composer.lock composer.json /var/www/html/

# Set working directory
WORKDIR /var/www/html/
RUN rm /etc/apt/preferences.d/no-debian-php

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libzip-dev \
    libonig-dev \
    zlib1g-dev  \
    libpq-dev \
    locales \
    zip \
    nano \
    unzip \
    git \
    curl \
    php-json \
    php-common \
    php-mysql \
    php-zip \
    php-gd \
    php-mbstring \
    php-curl \
    php-xml \
    php-pear \
    php-bcmath \
    libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
            && docker-php-ext-configure gd \
                     --with-freetype=/usr/include/ \
                     --with-jpeg=/usr/include/ \
                   && docker-php-ext-install -j$(nproc) gd \
                   && docker-php-ext-enable gd

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo pdo_mysql mbstring zip exif
RUN apt-get update && apt-get install -y libmemcached-dev \
    && pecl install memcached \
    && docker-php-ext-enable memcached

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Add user for Yii2 application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Copy existing application directory contents
COPY . /var/www/html/

# Copy existing application directory permissions
COPY --chown=www:www . /var/www/html/

# Change current user to www
USER www

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]