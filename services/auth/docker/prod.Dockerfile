FROM php:7.1-apache

# Set default system timezone
RUN ln -sf /usr/share/zoneinfo/Europe/Paris /etc/localtime

# Install last update and php extension
RUN apt-get update && apt-get install -y \
    git \
    vim \
    bzip2 \
    zip \
    libbz2-dev \
    libmcrypt-dev \
    libicu-dev \
    && docker-php-ext-configure mysqli \
    && docker-php-ext-install mysqli pdo_mysql bz2 mcrypt intl

# Enable Apache Rewrite module
RUN a2enmod rewrite

# Default Vhost for developement
COPY docker/vhost.conf /etc/apache2/sites-available/000-default.conf

# Insert the application into the container
RUN rm -rf /var/www/html && mkdir -p /var/www/html/vendor /var/www/html/public /var/www/html/api /var/www/html/config
COPY continuousphp.package composer.json /var/www/html/
COPY api /var/www/html/api
COPY config /var/www/html/config
COPY public /var/www/html/public
COPY vendor /var/www/html/vendor

RUN rm -rf /var/www/html/docker

CMD ["apache2-foreground"]