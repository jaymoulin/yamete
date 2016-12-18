FROM php:7.0-cli
COPY . /usr/src/myapp
WORKDIR /usr/src/myapp
CMD [ "php", "./index.php" ]