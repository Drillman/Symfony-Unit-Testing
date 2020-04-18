FROM php:fpm-alpine

RUN cp $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini
RUN sed -i "s/;\(extension=pdo_pgsql\)/\1/g" $PHP_INI_DIR/php.ini
RUN sed -i "s/;\(extension=pgsql\)/\1/g" $PHP_INI_DIR/php.ini

RUN apk update && apk add --no-cache \
        postgresql-dev \
        php-xsl \
        composer \
        libxslt-dev \
        libgcrypt-dev \
        libgpg-error-dev \
    && docker-php-ext-install -j$(nproc) pgsql \
    && docker-php-ext-install -j$(nproc) pdo_pgsql \
    && docker-php-ext-install -j$(nproc) xsl

RUN addgroup -g 1000 -S user
RUN adduser -h /home/user -g "" -s /bin/sh -G user -S -D -u 1000 user

USER user

WORKDIR /home/user