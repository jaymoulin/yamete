FROM php:7.0-fpm-alpine

MAINTAINER Jay MOULIN <jaymoulin@gmail.com>

ENV PATH="/root:$PATH"

COPY . /root
WORKDIR /root
RUN apk add git zip wget --update --virtual .build-deps && \
wget https://raw.githubusercontent.com/composer/getcomposer.org/1b137f8bf6db3e79a38a5bc45324414a6b1f9df2/web/installer -O - -q | php -- --quiet && \
php composer.phar install --no-dev -o && \
rm composer.phar && \
apk del wget --purge .build-deps

VOLUME ['/root/downloads']

CMD [ "download" ]
