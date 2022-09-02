FROM nginx:1.21-alpine

COPY ./docker/web/vhost.conf /etc/nginx/conf.d/default.conf

COPY ./www/ms_babel /srv/www
WORKDIR /srv/www