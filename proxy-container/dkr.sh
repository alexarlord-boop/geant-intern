#!/bin/bash
docker rm -f proxy
docker rmi -f proxy
# Remove unused Docker objects including build cache
docker system prune -f --all --volumes

docker build -t proxy .

docker run -d -p 8000:8000 -p 80:80 -p 443:443 \
         -v /proxyconfigs/letsencrypt/:/etc/letsencrypt/ \
         -v /proxyconfigs/simplesamlphp/config/:/var/simplesamlphp/config/ \
         -v /proxyconfigs/simplesamlphp/metadata/:/var/simplesamlphp/metadata/ \
         -v /proxyconfigs/simplesamlphp/cert/:/var/simplesamlphp/cert/ \
         -v /proxyconfigs/simplesamlphp/modules/mymodule:/var/simplesamlphp/modules/mymodule \
         -v /proxyconfigs/laravel-app/public/uploads:/var/www/laravel-app/public/uploads \
         --name proxy proxy

