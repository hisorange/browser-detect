FROM composer:latest as build-stage
WORKDIR /srv
COPY ./src .
COPY ./composer.json .
RUN composer install --ignore-platform-reqs --no-dev --no-suggest -o

FROM php:7.3-apache as demo-stage
COPY --from=build-stage /srv/ /var/www/html/
COPY index.php /var/www/html/

# TEST-STAGE