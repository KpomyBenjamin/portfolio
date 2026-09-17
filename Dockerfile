FROM php:8.5-cli

WORKDIR /app

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction

COPY . .

CMD ["sh", "-c", "php -S 0.0.0.0:${PORT:-10000} -t /app"]