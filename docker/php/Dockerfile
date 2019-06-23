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

ENV current_os=linux
RUN version=$(php -r "echo PHP_MAJOR_VERSION.PHP_MINOR_VERSION;") \
    && curl -A "Docker" -o /tmp/blackfire-probe.tar.gz -D - -L -s https://blackfire.io/api/v1/releases/probe/php/$current_os/amd64/$version \
    && mkdir -p /tmp/blackfire \
    && tar zxpf /tmp/blackfire-probe.tar.gz -C /tmp/blackfire \
    && mv /tmp/blackfire/blackfire-*.so $(php -r "echo ini_get('extension_dir');")/blackfire.so \
    && printf "extension=blackfire.so\nblackfire.agent_socket=tcp://blackfire:8707\n" > $PHP_INI_DIR/conf.d/blackfire.ini \
    && rm -rf /tmp/blackfire /tmp/blackfire-probe.tar.gz

WORKDIR /var/www/symfony

RUN usermod -u 1000 www-data

ENTRYPOINT ["/usr/bin/entrypoint.sh"]
