# Proxy container for SP automation deployment
## Deployment Guide

### Inside Docker Container
After creating the container you need to run the following commands:
```
docker exec -it proxy /usr/bin/php /var/www/laravel-app/artisan migrate
docker exec -it proxy chown -R www-data:www-data /var/www/laravel-app
```
