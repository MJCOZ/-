#!/usr/bin/env bash
set -e

cd /var/www/html

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

# تشغيل الهجرات
php artisan migrate --force

# تحسين الأداء للإنتاج (كاش الإعدادات والمسارات والواجهات)
php artisan config:cache
php artisan route:cache
php artisan view:cache

exec "$@"
