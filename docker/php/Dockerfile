FROM php:8.1-fpm-alpine

RUN apk upgrade --update \
    && apk add --update --no-cache autoconf g++ make libxml2-dev libzip libzip-dev zlib	zlib-dev \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && docker-php-ext-install pdo_mysql mysqli soap zip

COPY ./www/ms_babel /srv/www
WORKDIR /srv/www

CMD ["php-fpm"]