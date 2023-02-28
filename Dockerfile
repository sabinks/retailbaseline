FROM php:7.2 as php

RUN apt-get update -y
RUN apt-get install -y unzip libpq-dev libcurl4-gnutls-dev libpng-dev zlib1g-dev
RUN docker-php-ext-install gd zip pdo pdo_mysql mysqli bcmath

RUN pecl install -o -f redis \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable redis

WORKDIR /var/www
COPY . .

COPY --from=composer:2.0 /usr/bin/composer /usr/bin/composer

ENV PORT=9000
ENTRYPOINT [ "docker/entrypoint.sh" ]
CMD ["php","artisan","serve","--host=0.0.0.0"]

# ==============================================================================
#  node
FROM node:14-alpine as node

WORKDIR /var/www
COPY . .

RUN npm install --global cross-env
RUN npm install
RUN npm run prod

VOLUME /var/www/node_modules
