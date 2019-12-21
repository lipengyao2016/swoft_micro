FROM swoft/swoft_kafka_server:1.1

LABEL maintainer="lpy <yao50cn@163.com>" version="2.6"

ADD ./mongodb.ini /usr/local/etc/php/conf.d/
ADD ./mongodb.so /usr/local/lib/php/extensions/no-debug-non-zts-20170718/


WORKDIR /var/www/swoft
EXPOSE 18306 18307 18308

CMD ["tail","-f", "/var/www/swoft/README.md"]