# Pull nginx base image
FROM php:8.2-apache

# Support rewrite
RUN a2enmod rewrite

# Support pdo_mysql
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Support GD
RUN apt-get update && apt-get install -y \
        libjpeg-dev \
        libpng-dev \
        libwebp-dev \
    && docker-php-ext-configure gd --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

# Copy static assets into var/www
COPY ./webpage /var/www/html

# COPY ./file-server /var/www/html/file-server
# RUN echo "Start chmod 777"
# RUN chmod 777 /var/www/html/file-server
# RUN echo "chomd 777 completed"

# Expost port 80
EXPOSE 80