FROM php:7.3.6-cli

ADD . /srv
WORKDIR /srv

RUN \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    composer install
