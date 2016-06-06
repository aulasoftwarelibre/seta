FROM phusion/baseimage

MAINTAINER Sergio Gómez <sergio@uco.es>

EXPOSE 9001

RUN add-apt-repository ppa:ondrej/php
RUN apt-get update
RUN apt-get install -y --force-yes git php php-curl php-fpm php-gd php-intl php-mcrypt php-mysql php-pgsql php-readline php-sqlite3 php-xdebug php-xml
RUN apt-get install -y --force-yes php-mbstring php-bcmath

# Scripts
RUN mkdir -p /etc/my_init.d
ADD deploy.sh /etc/my_init.d/deploy.sh
RUN mkdir -p /etc/service/phpfpm
ADD phpfpm.sh /etc/service/phpfpm/run
RUN cd /usr/bin && curl -sS https://getcomposer.org/installer | php

# PHP+Symfony Configuration
ADD symfony.ini /etc/php/7.0/fpm/conf.d/
ADD symfony.ini /etc/php/7.0/cli/conf.d/
ADD symfony.pool.conf /etc/php/7.0/fpm/pool.d/
RUN rm /etc/php/7.0/fpm/pool.d/www.conf
RUN mkdir /run/php

# Security
RUN usermod -u 501 www-data

# Use baseimage-docker's init system.
CMD ["/sbin/my_init"]

# Clean up APT when done.
RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*
