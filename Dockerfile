# ---- مرحلة بناء الأصول (Vite غير مستخدم، البناء يعتمد PHP فقط) ----
FROM php:8.4-apache AS app

# تثبيت إضافات النظام و PHP المطلوبة
RUN apt-get update && apt-get install -y --no-install-recommends \
        git unzip libzip-dev libpng-dev libonig-dev libicu-dev libsqlite3-dev libpq-dev \
    && docker-php-ext-install pdo_mysql pdo_pgsql pdo_sqlite zip gd intl bcmath mbstring \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

# توجيه Apache إلى مجلد public
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# تثبيت الاعتماديات أولاً للاستفادة من طبقات الكاش
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --no-interaction

# نسخ بقية المشروع
COPY . .
RUN composer dump-autoload --optimize --no-dev \
    && mkdir -p storage/framework/{cache,sessions,views} storage/logs database \
    && chown -R www-data:www-data storage bootstrap/cache database \
    && chmod -R 775 storage bootstrap/cache

# سكربت الإقلاع
COPY docker/entrypoint.sh /usr/local/bin/entrypoint
RUN chmod +x /usr/local/bin/entrypoint

EXPOSE 80
ENTRYPOINT ["entrypoint"]
CMD ["apache2-foreground"]
