FROM php:7.4-apache

# Fix archived debian bullseye repository and install system dependencies
RUN sed -i '/bullseye-security/d' /etc/apt/sources.list \
    && apt-get update && apt-get install -y --no-install-recommends \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    zip \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions required by Laravel & PostgreSQL
RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd

# Configure Apache
RUN a2enmod rewrite \
    && echo "ServerName localhost" >> /etc/apache2/apache2.conf \
    && sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Install Composer
ENV COMPOSER_ALLOW_SUPERUSER=1
COPY --from=composer:2.2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy the full application first so database/ and app/ exist for composer autoloader
COPY . /var/www/html

# Install dependencies and build optimized autoloader
RUN composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction --ignore-platform-reqs --no-scripts

# Set directory permissions for Laravel storage and cache
RUN chmod +x /var/www/html/docker-entrypoint.sh \
    && mkdir -p /var/www/html/storage/framework/cache \
    && mkdir -p /var/www/html/storage/framework/sessions \
    && mkdir -p /var/www/html/storage/framework/views \
    && mkdir -p /var/www/html/storage/logs \
    && mkdir -p /var/www/html/bootstrap/cache \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

ENTRYPOINT ["/var/www/html/docker-entrypoint.sh"]
CMD ["apache2-foreground"]
