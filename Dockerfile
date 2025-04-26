# Utiliser une image PHP avec PHP-FPM
FROM php:8.2-fpm

# Installer les dépendances système
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql

# Installer Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Définir le répertoire de travail
WORKDIR /var/www

# Copier les fichiers de dépendances pour optimiser le cache
COPY composer.json composer.lock ./

# Installer les dépendKILLances Composer
RUN composer install --no-scripts --no-autoloader --ignore-platform-reqs

# Copier le reste de l'application
COPY . .

# Générer l'autoloader
RUN composer dump-autoload

# Copier le script d'entrée
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Donner les permissions initiales
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
RUN chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Exposer le port
EXPOSE 9000

# Utiliser le script d'entrée
ENTRYPOINT ["/entrypoint.sh"]

# Commande par défaut
CMD ["php-fpm"]