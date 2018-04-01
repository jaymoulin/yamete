FROM php:alpine3.7
COPY qemu-*-static /usr/bin/
LABEL maintainer="Jay MOULIN <jaymoulin@gmail.com> <https://twitter.com/MoulinJay>"

ENV PATH="/root:$PATH"

COPY . /root
WORKDIR /root
RUN apk add zlib-dev --update --no-cache && \
docker-php-ext-install -j$(nproc) zip && \
apk add wget git --virtual .build-deps && \
wget https://raw.githubusercontent.com/composer/getcomposer.org/1b137f8bf6db3e79a38a5bc45324414a6b1f9df2/web/installer -O - -q | php -- --quiet && \
php composer.phar install --no-dev -o && \
rm composer.phar && \
apk del wget --purge .build-deps

VOLUME ['/root/downloads']

CMD [ "download" ]
