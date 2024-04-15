FROM php:8.3-cli-alpine

ENV SWOOLE_VERSION v22.1.2

RUN apk add --no-cache postgresql-dev git \
    && git clone --branch $SWOOLE_VERSION --depth 1 https://github.com/openswoole/ext-openswoole.git /usr/src/php/ext/openswoole \
    && docker-php-ext-configure openswoole --with-postgres=/usr/local/pgsql \
    && docker-php-ext-install openswoole opcache \
    && apk del git

RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

COPY ./docker/production/php/conf.d /usr/local/etc/php/conf.d

ENV COMPOSER_ALLOW_SUPERUSER 1

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

ENTRYPOINT ["php", "server/index.php"]

RUN addgroup -g 1000 app && adduser -u 1000 -G app -s /bin/sh -D app

WORKDIR /app

COPY ./composer.json ./composer.lock ./

RUN composer install --no-dev --prefer-dist --no-progress --no-suggest --optimize-autoloader \
    && rm -rf /root/.composer/cache

COPY ./ ./

HEALTHCHECK --interval=5s --timeout=3s --start-period=5s CMD curl --fail http://127.0.0.1:80/health || exit 1
