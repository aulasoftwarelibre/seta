#!/usr/bin/env bash

echo "Init php-fpm daemon"

php5-fpm -c /etc/php5/fpm/php.ini --fpm-config /etc/php5/fpm/php-fpm.conf -F
