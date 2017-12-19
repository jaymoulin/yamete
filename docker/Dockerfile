FROM alpine

LABEL maintainer="Jay MOULIN <jaymoulin@gmail.com> <http://twitter.com/MoulinJay>"

ENV PATH="/root:$PATH"

COPY . /root
WORKDIR /root
RUN apk add php7 php7-openssl php7-iconv php7-phar php7-json php7-zlib php7-tokenizer php7-dom php7-mbstring php7-xml php7-simplexml php7-xmlwriter php7-curl php7-zip --update --no-cache && \
apk add wget git --virtual .build-deps && \
wget https://raw.githubusercontent.com/composer/getcomposer.org/1b137f8bf6db3e79a38a5bc45324414a6b1f9df2/web/installer -O - -q | php -- --quiet && \
php composer.phar install --no-dev -o && \
rm composer.phar && \
apk del wget --purge .build-deps

VOLUME ['/root/downloads']

CMD [ "download" ]
