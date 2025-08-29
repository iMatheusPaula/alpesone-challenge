FROM php:8.4-fpm AS base

RUN apt-get update && apt-get install -y \
    cron \
    curl \
    libpng-dev \
    libonig-dev \
    zip \
    unzip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql mbstring

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Development stage
FROM base AS development

COPY . .

RUN  composer install --no-interaction

COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["docker-entrypoint.sh"]

CMD ["php-fpm"]

# Testing stage
FROM base AS testing

COPY . .

RUN composer install --no-interaction

# Production stage
FROM base AS production

COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

CMD ["php-fpm"]