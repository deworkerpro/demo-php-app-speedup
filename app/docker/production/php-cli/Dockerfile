FROM php:8.3-cli-alpine AS builder

RUN apk add --no-cache linux-headers nodejs npm \
    && npm install -g sass \
    && docker-php-ext-install sockets

ENV COMPOSER_ALLOW_SUPERUSER 1

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

WORKDIR /app

COPY ./composer.json ./composer.lock ./

RUN composer install --no-scripts --prefer-dist --no-progress --no-suggest --optimize-autoloader \
    && rm -rf /root/.composer/cache

COPY ./ ./

RUN php bin/console sass:build

### CLI ###

FROM php:8.3-cli-alpine

RUN apk add --no-cache linux-headers \
    && docker-php-ext-install sockets opcache

RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

COPY ./docker/common/wait-for-it.sh /usr/local/bin/wait-for-it
RUN chmod 555 /usr/local/bin/wait-for-it

ENV COMPOSER_ALLOW_SUPERUSER 1

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

RUN addgroup -g 1000 app && adduser -u 1000 -G app -s /bin/sh -D app

WORKDIR /app

COPY ./composer.json ./composer.lock ./

RUN composer install --no-dev --no-scripts --prefer-dist --no-progress --no-suggest --optimize-autoloader \
    && rm -rf /root/.composer/cache

COPY --from=builder /app/var/sass ./var/sass
COPY ./ ./

RUN php bin/console assets:install public
RUN php bin/console asset-map:compile
RUN php bin/console cache:warmup

RUN chown app:app ./var -R

USER app
