FROM gustavofreze/php:8.0.6-fpm

RUN apk update  \
    && apk add libressl-dev \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb \
    && rm -rf /var/cache/apk/*
