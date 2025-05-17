# Étape de construction
FROM php:8.2-fpm

# Install Nginx and dependencies
RUN apt-get update && apt-get install -y \
    nginx \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql \
    && pecl install redis \
    && docker-php-ext-enable redis

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www

# Copy Composer files and install dependencies
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --ignore-platform-reqs

# Copy application code
COPY . .

# Optimize Composer autoloader
RUN composer dump-autoload --no-dev --optimize

# Cache Laravel configs and routes
RUN php artisan route:cache

# Copier le script d'entrée
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Create storage directories
RUN mkdir -p /var/www/storage/framework/cache \
    && mkdir -p /var/www/storage/framework/views \
    && mkdir -p /var/www/storage/fonts \
    && mkdir -p /var/www/storage/logs \
    && chown -R www-data:www-data /var/www/storage \
    && chmod -R 775 /var/www/storage

# Copy Nginx configuration
COPY nginx.conf /etc/nginx/sites-available/default

# Ensure proper permissions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage

# Expose port 80 for HTTP
EXPOSE 80

ENTRYPOINT ["/entrypoint.sh"]

# Start Nginx and PHP-FPM
CMD service nginx start && php-fpm