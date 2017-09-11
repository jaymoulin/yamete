FROM dpvduncan/rpi-alpine
COPY . /root
WORKDIR /root
ENV PATH="/root:$PATH"

RUN apk add php7 git zip php7-openssl php7-iconv php7-phar php7-json php7-zlib php7-tokenizer --update --no-cache && \
apk add wget --virtual .build-deps && \
wget https://raw.githubusercontent.com/composer/getcomposer.org/1b137f8bf6db3e79a38a5bc45324414a6b1f9df2/web/installer -O - -q | php -- --quiet && \
php composer.phar install && \
apk del wget --purge .build-deps

VOLUME ['/root/downloads']

CMD [ "download" ]
