FROM gustavofreze/php:8.2-fpm

RUN apk update  \
    && apk add libressl-dev \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb \
    && rm -rf /var/cache/apk/*
