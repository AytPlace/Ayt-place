FROM php:7.2-fpm

RUN apt-get update && apt-get install -y git zip wget
RUN touch /tmp/jeremy.test

ADD .ssh/ /root/.ssh/
ADD entrypoint.sh /usr/bin/entrypoint.sh

RUN chmod 777 /usr/bin/entrypoint.sh

# Intl extension
RUN apt-get install -y \
        libicu-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install -j$(nproc) intl

RUN docker-php-ext-install pdo_mysql

WORKDIR /var/www/symfony

RUN usermod -u 1000 www-data

ENTRYPOINT ["/usr/bin/entrypoint.sh"]
