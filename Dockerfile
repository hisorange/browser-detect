# Build with composer.
FROM composer:latest as build-stage
WORKDIR /srv
COPY ./composer.json .
RUN composer install --ignore-platform-reqs --no-dev --no-suggest -o

# Demo site.
FROM php:7.3-apache as demo-stage
COPY src/ /var/www/src/
COPY --from=build-stage /srv/vendor /var/www/vendor
COPY demo.php /var/www/html/index.php