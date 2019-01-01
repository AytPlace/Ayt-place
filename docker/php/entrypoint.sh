#!/bin/bash

if [[ $SYMFONY_ENV =~ (.*)prod ]]
then
	chmod 600 /root/.ssh/id_rsa

    mkdir /tmp/tempo
    git clone -b ${SYMFONY_ENV} git@gitlab.sooyoos.com:sooyoos/docker.git /tmp/tempo/ >> /dev/null
    cp -R /tmp/tempo/* /var/www/symfony/

    #set env
    cp -f /tmp/tempo/.env.${SYMFONY_ENV} /var/www/symfony/.env

    #deploy symfony
    cd /var/www/symfony/ && composer install --prefer-dist --no-interaction --optimize-autoloader
    cd /var/www/symfony/ && php bin/console doctrine:schema:update --force --env=${SYMFONY_ENV}

	#GULP
	cd /var/www/symfony/assets/gulp && npm install
	cd /var/www/symfony/assets/gulp && gulp delivery

	chown 1000:33 -R /var/www/symfony/
fi



if [ $SYMFONY_ENV != "prod" ]
then
    #Xdebug
	pecl install xdebug
	echo "error_reporting = E_ALL" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
	echo "display_startup_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
	echo "display_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
	echo "xdebug.remote_enable=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
	echo "xdebug.remote_connect_back=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
	echo "xdebug.idekey=\"PHPSTORM\"" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
	echo "xdebug.remote_port=9001" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
	
	#Robotx.txt
	echo -e "User-agent: Twitterbot/1.0 \nDisallow:\n\nUser-agent: *\nDisallow: /" > /var/www/symfony/public/robots.txt
fi

php-fpm -F
