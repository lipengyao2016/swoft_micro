#!/bin/sh
php /var/www/swoft/bin/swoft agent:index
sleep 2s && php /var/www/swoft/bin/swoft http:start