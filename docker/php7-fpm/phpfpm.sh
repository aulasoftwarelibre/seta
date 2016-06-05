#!/usr/bin/env bash

echo "Init php-fpm daemon"
#This is the lib path were the instantclient is installed
ORACLE_HOME=/usr/lib/oracle/12.1/client64
#This locates C headers from your instantclient
C_INCLUDE_PATH=/usr/include/oracle/12.1/client64
#I've never had trouble with that but it might save you a couple debugging hours
LD_LIBRARY_PATH=$ORACLE_HOME/lib
#This is really important as that php-fpm does not set this variable comparing to apache that does (1)
NLS_LANG=SPANISH_SPAIN.UTF8

export ORACLE_HOME LD_LIBRARY_PATH NLS_LANG
php-fpm7.0 -c /etc/php/7.0/fpm/php.ini --fpm-config /etc/php/7.0/fpm/php-fpm.conf -F
