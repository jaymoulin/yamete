FROM php:7.0-cli
COPY . /root
WORKDIR /root
ENV PATH="/root:$PATH"

RUN apt-get update && apt-get install git zip wget -y && \
wget https://raw.githubusercontent.com/composer/getcomposer.org/1b137f8bf6db3e79a38a5bc45324414a6b1f9df2/web/installer -O - -q | php -- --quiet && \
php composer.phar install && \
apt-get remove wget -y

VOLUME ['/root/downloads']

CMD [ "download" ]
