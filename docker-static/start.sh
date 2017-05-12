#!/bin/bash

# Run basic shell script within the container to start nginx, fpm, ...
/usr/bin/supervisord -n -c /etc/supervisord.conf &

cd /usr/share/nginx/html/
ls -l /var/log/nginx/friederike-raum-error.log
cat /etc/supervisord.conf
dpkg -l | grep libc

while :; do
  sleep 300
done

