FROM php:7.2-alpine
COPY qemu-*-static /usr/bin/
ARG VERSION=1.0.1
LABEL maintainer="Jay MOULIN <jaymoulin@gmail.com> <https://twitter.com/MoulinJay>"
LABEL version=${VERSION}

ENV PATH="/app:$PATH"

COPY . /app
WORKDIR /app
RUN apk add zlib-dev libzip-dev --update --no-cache && \
chmod go+wx /app && \
docker-php-ext-install -j$(nproc) zip && \
apk add wget git --virtual .build-deps && \
wget https://raw.githubusercontent.com/composer/getcomposer.org/1b137f8bf6db3e79a38a5bc45324414a6b1f9df2/web/installer -O - -q | php -- --quiet && \
php composer.phar install --no-dev -o && \
rm composer.phar && \
apk del wget --purge .build-deps

VOLUME ['/app/downloads']

CMD [ "download" ]
