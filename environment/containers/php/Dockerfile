FROM composer:lts AS composer

FROM php:8.3-fpm-alpine3.20 AS base_image
COPY --from=composer /usr/bin/composer /usr/bin/composer
RUN set -ex \
  && apk --no-cache add \
    postgresql-dev
RUN docker-php-ext-install pdo pdo_pgsql
WORKDIR /app

FROM base_image AS development
COPY ./containers/php/runners/entrypoint.development.sh /entrypoint.development.sh
RUN chmod +x /entrypoint.development.sh
CMD ["php-fpm"]
ENTRYPOINT ["/entrypoint.development.sh"]


