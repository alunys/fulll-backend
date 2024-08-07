ARG PHP_VERSION=8.3

FROM php:${PHP_VERSION}-cli

RUN apt-get update -qq \
    && apt-get install -y --force-yes -q --no-install-recommends \
        git \
    ;

WORKDIR /app

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN set -eux; \
    install-php-extensions \
        pdo_mysql \
    ;

# Composer
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV PATH="${PATH}:/root/.composer/vendor/bin"
COPY --from=composer/composer:2-bin --link /composer /usr/bin/composer
