FROM php:8.5-cli

WORKDIR /app

# Installer unzip pour permettre à Composer
# d'installer les dépendances
RUN apt-get update \
    && apt-get install -y unzip \
    && rm -rf /var/lib/apt/lists/*

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copier les fichiers Composer
COPY composer.json composer.lock ./

# Installer Resend, PHPDotenv et les autres dépendances
RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction

# Copier le reste du portfolio
COPY . .

# Lancer le serveur PHP sur le port fourni par Render
CMD ["sh", "-c", "php -S 0.0.0.0:${PORT:-10000} -t /app"]