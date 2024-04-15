FROM php:8.3-cli-alpine

RUN apk add --no-cache postgresql-dev bash coreutils \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-configure pcntl --enable-pcntl \
    && docker-php-ext-install pgsql pcntl

RUN mv $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini

COPY ./common/wait-for-it.sh /usr/local/bin/wait-for-it
RUN chmod 555 /usr/local/bin/wait-for-it

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

RUN addgroup -g 1000 app && adduser -u 1000 -G app -s /bin/sh -D app

WORKDIR /app

USER app
