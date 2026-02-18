FROM php:8.2-apache

RUN docker-php-ext-install pdo pdo_mysql mysqli
RUN apt-get update && apt-get install -y default-mysql-client && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite

COPY . /var/www/html/

COPY start.sh /start.sh
RUN chmod +x /start.sh

CMD ["/start.sh"]
