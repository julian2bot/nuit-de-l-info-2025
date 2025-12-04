# 1. Image PHP FPM
FROM php:8.4-fpm

# 2. Installer les dépendances et extensions
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    curl \
    default-mysql-client \
    libzip-dev \
    && docker-php-ext-install pdo pdo_mysql gd zip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 3. Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 4. Définir le répertoire de travail
WORKDIR /var/www/ndi_2025

# 5. Copier le code
COPY ndi_2025/ ./

# 6. Installer les dépendances PHP
RUN composer install --no-dev --optimize-autoloader
RUN php artisan key:generate

# 7. Permissions
RUN chown -R www-data:www-data storage bootstrap/cache

# 8. Exposer le port
EXPOSE 80

# 9. Lancer Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=80"]
