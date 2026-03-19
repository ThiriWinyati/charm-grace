FROM php:8.2-apache

RUN docker-php-ext-install mysqli
RUN a2enmod rewrite

COPY frontEnd/apache.config /etc/apache2/sites-available/000-default.conf
COPY . /var/www/html/

RUN chown -R www-data:www-data /var/www/html

EXPOSE 80