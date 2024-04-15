FROM php:8.3-cli-alpine

ENV SWOOLE_VERSION v22.1.2

RUN apk add --no-cache postgresql-dev git \
    && git clone --branch $SWOOLE_VERSION --depth 1 https://github.com/openswoole/ext-openswoole.git /usr/src/php/ext/openswoole \
    && docker-php-ext-configure openswoole --with-postgres=/usr/local/pgsql \
    && docker-php-ext-install openswoole \
    && apk del git

RUN mv $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini

RUN addgroup -g 1000 app && adduser -u 1000 -G app -s /bin/sh -D app

WORKDIR /app

USER app
