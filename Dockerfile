FROM eboraas/laravel
MAINTAINER t-joamou@microsoft


RUN apt-get update && apt-get install -y libssl-dev php-pear php5-dev

RUN mkdir -p /usr/local/openssl/include/openssl/ && \
    ln -s /usr/include/openssl/evp.h /usr/local/openssl/include/openssl/evp.h && \
    mkdir -p /usr/local/openssl/lib/ && \
    ln -s /usr/lib/x86_64-linux-gnu/libssl.a /usr/local/openssl/lib/libssl.a && \
    ln -s /usr/lib/x86_64-linux-gnu/libssl.so /usr/local/openssl/lib/

RUN pecl install mongodb
RUN echo "extension=mongodb.so" > /etc/php5/cli/php.ini
RUN echo "extension=mongodb.so" > /etc/php5/apache2/php.ini
RUN /etc/init.d/apache2 restart

RUN rm -r -f /var/www/
ADD . /var/www/laravel/

WORKDIR /var/www/laravel/
RUN composer install
