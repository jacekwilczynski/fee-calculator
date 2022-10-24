FROM php:8.1-alpine3.16

WORKDIR /var/www/html

# install composer
COPY --from=composer:2.4 /usr/bin/composer /usr/bin/composer

# install composer packages
ADD . /var/www/html
RUN composer install
