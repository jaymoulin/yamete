FROM php:7.4-alpine
COPY qemu-*-static /usr/bin/
ARG VERSION=1.9.0
LABEL maintainer="Jay MOULIN <jaymoulin@gmail.com> <https://twitter.com/MoulinJay>"
LABEL version=${VERSION}

ENV PATH="/app:$PATH"

COPY . /app
WORKDIR /app
RUN apk add zlib-dev libzip-dev libgd gd-dev libpng-dev --update --no-cache && \
chmod go+wx /app && \
docker-php-ext-install -j$(nproc) zip && \
docker-php-ext-install -j$(nproc) gd && \
docker-php-ext-install -j$(nproc) bcmath && \
apk add wget git --virtual .build-deps && \
wget https://raw.githubusercontent.com/composer/getcomposer.org/76a7060ccb93902cd7576b67264ad91c8a2700e2/web/installer -O - -q | php -- --quiet && \
php composer.phar install --no-dev -o && \
rm composer.phar && \
apk del wget --purge .build-deps

VOLUME ['/app/downloads']

CMD [ "download" ]
