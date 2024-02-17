FROM php:8.3-fpm
RUN apt update && apt install -y zlib1g-dev g++ git libicu-dev zip libzip-dev libpq-dev zip graphviz \
    && pecl install -o -f xdebug redis \
    && docker-php-ext-enable xdebug redis \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install intl opcache pdo_pgsql pgsql zip \
    && pecl clear-cache \
    && apt remove -y zlib1g-dev g++ \
    && rm -rf /tmp/pear /var/cache/*
WORKDIR /var/www/fast-track
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN curl -sS https://get.symfony.com/cli/installer | bash && \
    mv /root/.symfony5/bin/symfony /usr/local/bin/symfony
