# Stage 1: Build Laravel Backend
FROM php:8.2-fpm as backend-build
RUN apt-get update && apt-get install -y --no-install-recommends \
    libzip-dev \
    libpng-dev \
    libxml2-dev \
    libfreetype6-dev \
    libjpeg-dev \
    libpq-dev \
    libicu-dev \
    libxslt1-dev \
    git \
    zip \
    unzip \
    bash \
    curl \
    autoconf \
    gcc \
    g++ \
    make \
    pkg-config \
    libpq-dev \
    sqlite3 \
    libsqlite3-dev

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-configure intl && \
    docker-php-ext-install -j$(nproc) pdo_mysql pdo_sqlite zip soap gd intl opcache pcntl xsl

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

WORKDIR /var/www/laravel

# Step 1: Cache Composer
COPY ./composer.json ./composer.lock /var/www/laravel/
RUN composer install --no-interaction --prefer-dist --no-scripts

# Step 2: Copy Laravel
COPY ./ /var/www/laravel/
COPY ./.env.example /var/www/laravel/.env
COPY ./.env.example /var/www/laravel/.env.production

# Step 3: Run Composer
RUN composer dump-autoload --optimize --no-interaction

# Stage 2: Build Frontend
FROM node:22 AS frontend-build
WORKDIR /var/www/laravel

# Step 1: Copy the vendor for ziggy build
COPY --from=backend-build /var/www/laravel/vendor /var/www/laravel/vendor

# Step 2: Copy the Laravel project
COPY .env.example /var/www/laravel/.env
COPY ./ /var/www/laravel/

# Step 3: Install frontend dependencies and build
RUN npm install && npm run build

# Stage 3: Final Release
FROM backend-build as release

RUN apt-get update && apt-get install -y --no-install-recommends \
    nginx \
    supervisor

# Set the working directory
WORKDIR /var/www/laravel

# Copy backend files from build stage
COPY --from=backend-build /var/www/laravel /var/www/laravel

# Copy built frontend
COPY --from=frontend-build /var/www/laravel/bootstrap/ssr /var/www/laravel/bootstrap/ssr
COPY --from=frontend-build /var/www/laravel/public /var/www/laravel/public

# Copy Nginx/PHP config
COPY ./docker/site.conf /etc/nginx/sites-enabled/default
COPY ./docker/site.conf /etc/nginx/conf.d/default.conf
COPY ./docker/nginx.conf /etc/nginx/nginx.conf
COPY ./docker/php/php.ini /usr/local/etc/php/conf.d/docker-fpm.ini
COPY ./docker/supervisor/supervisord.conf /etc/supervisord.conf

# Permissions...
RUN chown -R www-data:www-data /var/www/laravel && \
    chmod -R 755 storage bootstrap/cache && \
    mkdir -p /run/nginx && chown -R www-data:www-data /run/nginx

# Copy entrypoint script into the same working directory
COPY entrypoint.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

# Expose ports
EXPOSE 80/tcp
EXPOSE 9000/tcp
EXPOSE 8080/tcp

ENTRYPOINT ["/bin/bash", "/usr/local/bin/start.sh"]

