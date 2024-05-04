FROM php:8.2-fpm as base

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

RUN install-php-extensions  \
    sqlite3 \
    pdo_pgsql  \
    pgsql  \
    bcmath  \
    intl  \
    zip  \
    pdo \
    pdo_mysql \
    @composer

RUN apt-get update \
    && apt-get -y --no-install-recommends install \
        curl \
        libzip-dev \
        unzip \
        wget \
        sqlite3

FROM base as local
RUN install-php-extensions  \
    @composer \
    xdebug

WORKDIR /var/www
