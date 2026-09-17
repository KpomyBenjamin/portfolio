FROM php:8.5-cli

WORKDIR /app

# Installer unzip pour Composer
RUN apt-get update \
    && apt-get install -y unzip \
    && rm -rf /var/lib/apt/lists/*

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copier les fichiers Composer
COPY composer.json composer.lock ./

# Installer les dépendances PHP
RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction

# Copier le portfolio
COPY . .

# Démarrer le serveur PHP
CMD ["sh", "-c", "php -S 0.0.0.0:${PORT:-10000} -t /app"]