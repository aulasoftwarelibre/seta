FROM phusion/baseimage

MAINTAINER Sergio Gómez <sergio@uco.es>

EXPOSE 9001

RUN add-apt-repository ppa:ondrej/php5-5.6
RUN apt-get update && apt-get install -y --force-yes git php5 php5-curl php5-fpm php5-gd php5-intl php5-mcrypt php5-mysqlnd php5-pgsql php5-readline php5-sqlite php5-xdebug wkhtmltopdf xvfb

# Scripts
RUN mkdir -p /etc/my_init.d
ADD deploy.sh /etc/my_init.d/deploy.sh
RUN mkdir -p /etc/service/phpfpm
ADD phpfpm.sh /etc/service/phpfpm/run
RUN cd /usr/bin && curl -sS https://getcomposer.org/installer | php
ADD wkhtmltopdf.wrapper /usr/bin/wkhtmltopdf.wrapper

# PHP+Symfony Configuration
ADD symfony.ini /etc/php5/fpm/conf.d/
ADD symfony.ini /etc/php5/cli/conf.d/
ADD symfony.pool.conf /etc/php5/fpm/pool.d/
RUN rm /etc/php5/fpm/pool.d/www.conf

# Security
RUN usermod -u 1000 www-data

# Use baseimage-docker's init system.
CMD ["/sbin/my_init"]

# Clean up APT when done.
RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*
