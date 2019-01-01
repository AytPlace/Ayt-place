#!/bin/bash
ln -s /etc/nginx/sites-available/symfony-${SYMFONY_ENV}.conf /etc/nginx/sites-enabled/symfony-${SYMFONY_ENV}.conf
rm /etc/nginx/sites-enabled/default
# Run nginx
nginx
