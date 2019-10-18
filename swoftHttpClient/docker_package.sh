#!/bin/sh
docker stop swoftclient && docker rm swoftclient
docker build -t swoft/traclient:4.3 .