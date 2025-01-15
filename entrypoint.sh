#!/bin/sh

chown -R www-data:www-data /var/www/
echo "Setting up Laravel..."
cd /var/www/laravel && php artisan key:generate
cd /var/www/laravel && php artisan migrate
exec supervisord -c /etc/supervisord.conf
echo "Starting supervisord..."
