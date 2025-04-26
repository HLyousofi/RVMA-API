#!/bin/bash
# Corriger les permissions
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Créer le lien symbolique
php artisan storage:link

# Exécuter la commande par défaut (php-fpm)
exec "$@"