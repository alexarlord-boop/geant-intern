#!/bin/bash
docker rm -f proxy
docker rmi -f proxy

docker build -t proxy .

docker run -d -p 80:80 -p 443:443 \
         -v /proxyconfigs/letsencrypt/:/etc/letsencrypt/ \
         -v /proxyconfigs/simplesamlphp/config/:/var/simplesamlphp/config/ \
         -v /proxyconfigs/simplesamlphp/metadata/:/var/simplesamlphp/metadata/ \
         -v /proxyconfigs/simplesamlphp/cert/:/var/simplesamlphp/cert/ \
         -v /proxyconfigs/simplesamlphp/modules/mymodule:/var/simplesamlphp/modules/mymodule \
         --name proxy proxy