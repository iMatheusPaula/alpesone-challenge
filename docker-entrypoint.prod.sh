#!/bin/sh
set -e

until php artisan migrate --force --no-interaction; do
  echo "🥱 mysql is not yet available..."
  sleep 2
done

php artisan config:cache

php artisan route:cache

echo '* * * * * cd /var/www && php artisan schedule:run >> /var/log/cron.log 2>&1' > /etc/cron.d/laravel-schedule
chmod 0644 /etc/cron.d/laravel-schedule
crontab /etc/cron.d/laravel-schedule

service cron start

exec php-fpm