#!/bin/sh

chown -R www-data:www-data /var/www/
echo "Setting up Laravel..."
cd /var/www/laravel && php artisan key:generate

until php -r "new PDO('mysql:host=db;dbname=csv_dashboard', 'dockeruser', 'dockerpassword');" > /dev/null 2>&1; do
    echo "Waiting for MySQL to be ready..."
    sleep 5
done

cd /var/www/laravel && php artisan migrate
exec supervisord -c /etc/supervisord.conf
echo "Starting supervisord..."
