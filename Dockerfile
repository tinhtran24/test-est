FROM php:7.4-cli
RUN docker-php-ext-install mysqli

COPY . /usr/src/est-test
WORKDIR /usr/src/est-test
CMD [ "php", "-S localhost:8000" ]