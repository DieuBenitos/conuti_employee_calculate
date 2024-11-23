FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    libicu-dev \
    libpq-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl pdo pdo_mysql zip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/symfony

# Copy Composer files to the container
COPY composer.json composer.lock ./

# Install PHP dependencies (including polyfills)
RUN composer install --no-scripts --no-autoloader
RUN composer require symfony/polyfill-intl-messageformatter --no-scripts --no-autoloader

# Copy the application code
COPY . .

# Run Composer's autoloader optimization
RUN composer dump-autoload --optimize

# Expose the port used by PHP-FPM
EXPOSE 9000

CMD ["php-fpm"]
