FROM php:8.2-cli-alpine

# INSTALL AND UPDATE COMPOSER
COPY --from=composer /usr/bin/composer /usr/bin/composer
RUN composer self-update

WORKDIR /usr/src/app
COPY . .

RUN sudo chown www-data:www-data -R ./storage
RUN sudo chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN sudo chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# INSTALL YOUR DEPENDENCIES
RUN composer install --prefer-dist