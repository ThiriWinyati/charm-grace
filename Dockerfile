FROM php:8.2-apache

RUN docker-php-ext-install pdo pdo_mysql mysqli

# Enable rewrite (safe to keep on)
RUN a2enmod rewrite

# Copy app into Apache root
COPY . /var/www/html/

# Start script to make Apache listen on Render's PORT
COPY start.sh /start.sh
RUN chmod +x /start.sh

CMD ["/start.sh"]
