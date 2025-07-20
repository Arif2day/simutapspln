FROM php:7.4-fpm

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip unzip curl git npm \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

# COPY .env.example .env

RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# RUN php artisan key:generate
RUN php artisan storage:link
RUN php artisan config:clear
RUN php artisan cache:clear
RUN php artisan view:clear
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]

# Optional:
# RUN php artisan migrate --force

EXPOSE 8000

CMD php artisan serve --host=0.0.0.0 --port=8000
