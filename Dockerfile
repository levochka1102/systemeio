FROM php:8.3-cli-alpine as sio_test
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/bin/
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN apk add --no-cache \
    git  \
    zip  \
    bash \
    && install-php-extensions \
    pdo_pgsql

# Setup php app user
ARG USER_ID=1000
RUN adduser -u ${USER_ID} -D app
USER app

COPY --chown=app . /app
WORKDIR /app

EXPOSE 8337

CMD ["php", "-S", "0.0.0.0:8337", "-t", "public"]
