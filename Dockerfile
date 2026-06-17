FROM php:8.4-apache

# ARG untuk UID/GID host
ARG UID=1000
ARG GID=1000

# Install dependencies
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    git \
    curl \
    nodejs \
    npm \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql zip gd

# Enable Apache rewrite
RUN a2enmod rewrite

# ===== BUAT USER =====
RUN groupadd -g ${GID} laravel \
    && useradd -u ${UID} -g laravel -m -s /bin/bash laravel

# Set Apache document root
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/000-default.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

WORKDIR /var/www/html

COPY . /var/www/html

# Permission
RUN chown -R laravel:laravel /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Psysh fix
ENV HOME=/home/laravel

RUN mkdir -p /home/laravel/.config/psysh \
    && chown -R laravel:laravel /home/laravel

EXPOSE 80