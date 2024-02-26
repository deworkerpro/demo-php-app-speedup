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

### FPM ###

FROM php:8.3-cli-alpine

RUN apk add --no-cache linux-headers \
    && docker-php-ext-install opcache sockets

RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

COPY ./docker/production/php/conf.d /usr/local/etc/php/conf.d

ENV COMPOSER_ALLOW_SUPERUSER 1

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer
COPY --from=ghcr.io/roadrunner-server/roadrunner:2023.3 /usr/bin/rr /usr/local/bin/rr

CMD ["rr", "serve"]

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

RUN chown www-data:www-data ./var -R

USER app

HEALTHCHECK --interval=5s --timeout=3s --start-period=5s CMD curl --fail http://127.0.0.1:2114/health?plugin=http || exit 1
