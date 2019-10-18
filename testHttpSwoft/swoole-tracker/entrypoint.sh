#!/usr/bin/env bash

/opt/swoole/script/php/swoole_php /opt/swoole/node-agent/src/node.php &

php /var/www/swoft/bin/swoft http:start