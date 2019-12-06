FROM swoft/swoft_kafka_server:1.1

LABEL maintainer="lpy <yao50cn@163.com>" version="2.6"


ADD . /var/www/swoft


RUN  cd /var/www/swoft \
    && composer install \
    && composer clearcache \
    && apt-get update && apt-get install -y procps

WORKDIR /var/www/swoft

EXPOSE 18306 18307 18308


#CMD ["sh", "./entrypoint.sh"]

CMD ["tail","-f", "/var/www/swoft/Dockerfile"]

#CMD nohup sh -c '/opt/swoole/script/php/swoole_php /opt/swoole/node-agent/src/node.php & && php /var/www/swoft/bin/swoft http:start'
#CMD ["php", "/var/www/swoft/bin/swoft", "http:start"]