# Imagen base con PHP-FPM
FROM php:8.2-cli

# Paquetes y extensiones necesarias para Laravel + PhpSpreadsheet
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
    libzip-dev unzip git curl \
 && docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install -j$(nproc) gd zip bcmath pdo pdo_mysql \
 && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Directorio de la app
WORKDIR /app
COPY . .

# Dependencias y optimizaciones de Laravel
RUN composer install --no-dev --optimize-autoloader \
 && mkdir -p storage/framework/{cache,sessions,views} \
 && chown -R www-data:www-data storage bootstrap/cache \
 && chmod -R 775 storage bootstrap/cache

# Comando de arranque: servidor embebido de PHP apuntando a /public
# Railway expone la var PORT; usamos 0.0.0.0:$PORT
CMD php -d variables_order=EGPCS -S 0.0.0.0:${PORT:-8080} -t public public/index.php
