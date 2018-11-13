# FROM php:7.2-apache
# COPY . /var/www/html/
# FROM php:7.2-apache
# COPY . /var/www/html/

FROM php:7.2-apache

RUN docker-php-ext-install pdo pdo_mysql
RUN a2enmod rewrite