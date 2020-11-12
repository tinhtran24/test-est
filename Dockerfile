FROM php:7.4-cli
COPY . /usr/src/est-test
WORKDIR /usr/src/est-test
CMD [ "php", "./index.php" ]