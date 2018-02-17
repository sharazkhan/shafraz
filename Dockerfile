FROM php:5.6-apache

USER root

COPY php.ini /usr/local/etc/php/

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

RUN apt-get update -y && apt-get install -y libpng-dev curl libcurl4-openssl-dev nano && \
    apt-get install libldap2-dev -y && \
    rm -rf /var/lib/apt/lists/* && \
    docker-php-ext-configure ldap --with-libdir=lib/x86_64-linux-gnu/ && \
    docker-php-ext-install ldap pdo pdo_mysql gd curl opcache zip

WORKDIR  /var/www/html

COPY . /var/www/html
#RUN chmod -R 777 .
#RUN composer update

#COPY 000-default.conf /etc/apache2/sites-available

RUN a2enmod rewrite
RUN service apache2 restart


#docker exec -it malvern_web php artisan migrate
