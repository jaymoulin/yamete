FROM php:7.0-fpm-alpine

MAINTAINER Jay MOULIN <jaymoulin@gmail.com>

COPY . /root
WORKDIR /root
ENV PATH="/root:$PATH"

RUN apk add git zip --update --no-cache && \
apk add wget --virtual .build-deps && \
wget https://raw.githubusercontent.com/composer/getcomposer.org/1b137f8bf6db3e79a38a5bc45324414a6b1f9df2/web/installer -O - -q | php -- --quiet && \
php composer.phar install --no-dev -o && \
apk del wget --purge .build-deps

VOLUME ['/root/downloads']

CMD [ "download" ]
