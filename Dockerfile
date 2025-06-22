FROM php:8.2-apache

# Povoliť .htaccess + mod_rewrite
RUN a2enmod rewrite && \
    sed -i 's/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

COPY app/ /var/www/html/
