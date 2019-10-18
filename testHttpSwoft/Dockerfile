FROM swoft/swoft

LABEL maintainer="lpy <yao50cn@163.com>" version="2.6"


ADD . /var/www/swoft

CMD ["sh", "./entrypoint.sh"]

#CMD ["sh", "./entrypoint.sh"]

#CMD nohup sh -c '/opt/swoole/script/php/swoole_php /opt/swoole/node-agent/src/node.php & && php /var/www/swoft/bin/swoft http:start'
#CMD ["php", "/var/www/swoft/bin/swoft", "http:start"]