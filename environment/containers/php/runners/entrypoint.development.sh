#!/bin/ash

# Instructions to follow in the dev container
composer install --optimize-autoloader

if [ ! -f /app/.env ]
then
    cp /app/.env.example /app/.env
fi

php /app/artisan key:generate
php /app/artisan optimize:clear

cd /app && sh cron.sh &

exec "$@"
