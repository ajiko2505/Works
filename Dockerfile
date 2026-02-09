FROM php:8.1-apache

# Install required extensions
RUN apt-get update && apt-get install -y libzip-dev unzip git \
    && docker-php-ext-install mysqli pdo_mysql \
    && a2enmod rewrite

WORKDIR /var/www/html

# Copy application files
COPY . /var/www/html/

RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

EXPOSE 80

CMD ["apache2-foreground"]
