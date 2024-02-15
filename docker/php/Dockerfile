FROM php:8.3-fpm-alpine3.18

# Essentials
RUN echo "UTC" > /etc/timezone

COPY ./docker/common/php/conf.d /usr/local/etc/php/conf.d
COPY ./docker/common/php/php-fpm.d /usr/local/etc/php/php-fpm.d
COPY ./docker/php/conf.d /usr/local/etc/php/conf.d
COPY ./docker/php/conf.d/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

### PHP extensions ###
RUN apk add --update linux-headers

### Install PostgreSQL PHP extensions ###
RUN set -ex \
  && apk --no-cache add postgresql-dev \
  && docker-php-ext-install pdo pdo_pgsql \
  && apk del postgresql-dev \
  && apk add --upgrade postgresql --update-cache --repository http://dl-3.alpinelinux.org/alpine/edge/main/
### Install PostgreSQL PHP extensions ###

RUN apk update && apk add autoconf g++ coreutils make bash libzip-dev zlib-dev libpng-dev openssl curl ca-certificates \
    # Xdebug, Redis
    && pecl install -o -f xdebug redis \
    && docker-php-ext-enable xdebug \
    && docker-php-ext-enable redis \
    && docker-php-ext-enable opcache \
    # Other
    && docker-php-ext-install gd \
    && docker-php-ext-install zip \
    && docker-php-ext-install opcache \
    && rm -rf /tmp/pear  \
    && apk cache clean
### PHP extensions ###

RUN ln -s /usr/bin/php83 /usr/bin/php

### Composer install ###
ENV COMPOSER_ALLOW_SUPERUSER 1
COPY --from=composer:lts /usr/bin/composer /usr/bin/composer
### Composer install ###

### host.docker.internal ###
COPY ./docker/common/php/entrypoint.sh /usr/local/bin/docker-php-entrypoint
RUN chmod +x /usr/local/bin/docker-php-entrypoint
### host.docker.internal ###

### wait-for-it ###
COPY ./docker/common/wait-for-it.sh /usr/local/bin/wait-for-it
RUN chmod +x /usr/local/bin/wait-for-it
### wait-for-it ###

WORKDIR $APP_PROJECT_PATH

RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

### PHP-FPM healthcheck  ###
HEALTHCHECK --interval=5s --timeout=3s --start-period=1s \
    CMD REDIRECT_STATUS=true SCRIPT_NAME=/ping SCRIPT_FILENAME=/ping REQUEST_METHOD=GET \
    cgi-fcgi -bind -connect 127.0.0.1:9000 || exit 1
### PHP-FPM healthcheck ###
