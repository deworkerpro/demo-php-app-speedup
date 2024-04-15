FROM php:8.3-cli-alpine

ENV SWOOLE_VERSION v22.1.2

RUN apk add --no-cache postgresql-dev bash coreutils git \
    && git clone --branch $SWOOLE_VERSION --depth 1 https://github.com/openswoole/ext-openswoole.git /usr/src/php/ext/openswoole \
    && docker-php-ext-configure openswoole --with-postgres=/usr/local/pgsql \
    && docker-php-ext-install openswoole \
    && apk del git

RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

COPY ./docker/common/wait-for-it.sh /usr/local/bin/wait-for-it
RUN chmod 555 /usr/local/bin/wait-for-it

ENV COMPOSER_ALLOW_SUPERUSER 1

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

RUN addgroup -g 1000 app && adduser -u 1000 -G app -s /bin/sh -D app

WORKDIR /app

COPY ./composer.json ./composer.lock ./

RUN composer install --no-dev --prefer-dist --no-progress --no-suggest --optimize-autoloader \
    && rm -rf /root/.composer/cache

COPY ./ ./

USER app
