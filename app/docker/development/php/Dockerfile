FROM php:8.3-cli-alpine

RUN apk add --no-cache linux-headers \
    && docker-php-ext-install sockets

RUN mv $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini

COPY --from=ghcr.io/roadrunner-server/roadrunner:2023.3 /usr/bin/rr /usr/local/bin/rr

RUN addgroup -g 1000 app && adduser -u 1000 -G app -s /bin/sh -D app

WORKDIR /app

USER app
