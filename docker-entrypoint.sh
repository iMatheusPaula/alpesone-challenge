#!/bin/sh
set -e

# Se não tiver o arquivo .env, cria um novo a partir do .env.example e gera a key do laravel
if [ ! -f .env ]; then
    echo "=> Creating .env file from .env.example..."
    cp .env.example .env

    echo "=> Generating application key..."
    php artisan key:generate --no-interaction --no-ansi --force
fi

# Espera o MySQL estar disponível para executar as migrations
until php artisan migrate --force --no-interaction; do
  echo "🥱 mysql is not yet available..."
  sleep 2
done

exec "$@"