#!/bin/bash

deploy()
{
    cd /var/www/symfony
    php app/console doctrine:schema:update --force --complete
    php app/console cache:clear
    chown -R www-data. /var/www/symfony/app/cache /var/www/symfony/app/logs
    cd -
}

set -e

host=$(env | grep DB_PORT_3306_TCP_ADDR | cut -d = -f 2)
port=$(env | grep DB_PORT_3306_TCP_PORT | cut -d = -f 2)

if [ -n "$host" ];
then
    echo -n "waiting for TCP connection to $host:$port..."

    while ! nc -w 1 $host $port 2>/dev/null
    do
      echo -n .
      sleep 1
    done

    deploy | true
fi
