FROM php:8.0-alpine
COPY qemu-*-static /usr/bin/
ARG VERSION=1.21.1
LABEL maintainer="Jay MOULIN <https://jaymoulin.me/me/yamete> <https://twitter.com/MoulinJay>"
LABEL version=${VERSION}

ENV PATH="/app:$PATH"

COPY . /app
WORKDIR /app
RUN apk add zlib-dev libzip-dev libgd gd-dev libpng-dev libwebp-dev libjpeg-turbo-dev --update --no-cache && \
chmod go+wx /app && \
docker-php-ext-configure gd --with-webp --with-jpeg && \
docker-php-ext-install -j$(nproc) gd && \
docker-php-ext-install -j$(nproc) zip


CMD [ "download" ]
