FROM php:7.3-apache

RUN apt-get update && apt-get upgrade -y
RUN docker-php-ext-install pdo pdo_mysql

COPY . /var/www/html

EXPOSE 80