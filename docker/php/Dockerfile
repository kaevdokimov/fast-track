FROM php:8.3-fpm
RUN apt-get update && apt-get install -y zlib1g-dev g++ git libicu-dev zip libzip-dev libpq-dev zip \
    && pecl install -o -f xdebug redis \
    && docker-php-ext-enable xdebug redis \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install intl opcache pdo_pgsql pgsql zip \
    && pecl clear-cache \
    && rm -rf /tmp/pear /var/cache/*
WORKDIR /var/www/fast-track
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN mv /root/.symfony5/bin/symfony /usr/local/bin/symfony
