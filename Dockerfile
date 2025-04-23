# Utiliser une image PHP avec les extensions nécessaires
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

# Copier composer.json et composer.lock en premier pour optimiser le cache
COPY composer.json composer.lock ./

# Installer les dépendances Composer
RUN composer install --no-scripts --no-autoloader --ignore-platform-reqs

# Copier le reste des fichiers
COPY . .

# Générer l'autoloader et exécuter les scripts Composer
RUN composer dump-autoload --optimize

# Donner les permissions appropriées
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
RUN chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Exposer le port (facultatif, car Nginx gère cela)
EXPOSE 9000

# Commande pour démarrer PHP-FPM
CMD ["php-fpm"]