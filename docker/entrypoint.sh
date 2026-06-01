#!/usr/bin/env bash
set -e

cd /var/www/html

# بعض منصّات الاستضافة تحقن منفذاً عبر $PORT — اجعل Apache يستمع عليه
if [ -n "${PORT}" ] && [ "${PORT}" != "80" ]; then
    sed -ri "s/^Listen 80$/Listen ${PORT}/" /etc/apache2/ports.conf
    sed -ri "s/:80>/:${PORT}>/" /etc/apache2/sites-available/000-default.conf
fi

# توليد مفتاح التطبيق إن لم يكن موجوداً
if [ -z "${APP_KEY}" ] && ! grep -q "^APP_KEY=base64" .env 2>/dev/null; then
    php artisan key:generate --force || true
fi

# قاعدة بيانات SQLite: إنشاء الملف إن كان هذا هو الاتصال
if [ "${DB_CONNECTION:-sqlite}" = "sqlite" ]; then
    DB_FILE="${DB_DATABASE:-/var/www/html/database/database.sqlite}"
    touch "${DB_FILE}"
    chown www-data:www-data "${DB_FILE}"
fi

# ربط مجلد التخزين العام (للبوسترات المرفوعة)
php artisan storage:link || true

# تشغيل الهجرات وبذر بيانات أولية (البذر آمن للتكرار: يتوقف إن كانت القاعدة معبّأة)
php artisan migrate --force --seed

# تحسين الأداء للإنتاج (كاش الإعدادات والمسارات والواجهات)
php artisan config:cache
php artisan route:cache
php artisan view:cache

exec "$@"
