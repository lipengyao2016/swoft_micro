#!/bin/sh
docker stop myswoft && docker rm myswoft
docker build -t registry.cn-shenzhen.aliyuncs.com/shenj/swoft_server:1.3 .