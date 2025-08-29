#!/bin/sh
set -e

# Espera o MySQL estar disponível para executar as migrations
until php artisan migrate --force --no-interaction; do
  echo "🥱 mysql is not yet available..."
  sleep 2
done

# Configurações de cache para produção
php artisan config:cache
php artisan route:cache

# Configura o cron job para rodar o scheduler do Laravel a cada minuto
echo '* * * * * cd /var/www && /usr/local/bin/php artisan schedule:run >> /var/log/cron.log 2>&1' > /etc/cron.d/laravel-schedule
chmod 0644 /etc/cron.d/laravel-schedule
crontab /etc/cron.d/laravel-schedule

# Inicia o cron
service cron start

exec "$@"