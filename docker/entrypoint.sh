#!/usr/bin/env bash
set -e

cd /var/www/html

# بعض منصّات الاستضافة تحقن منفذاً عبر $PORT — اجعل Apache يستمع عليه
if [ -n "${PORT}" ] && [ "${PORT}" != "80" ]; then
    sed -ri "s/^Listen 80$/Listen ${PORT}/" /etc/apache2/ports.conf
    sed -ri "s/:80>/:${PORT}>/" /etc/apache2/sites-available/000-default.conf
fi

# توليد مفتاح التطبيق إن لم يُضبط عبر البيئة (يُفضّل ضبط APP_KEY في إعدادات الاستضافة)
if [ -z "${APP_KEY}" ]; then
    [ -f .env ] || echo "APP_KEY=" > .env
    php artisan key:generate --force
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

# تأسيس أول حساب مدير من المتغيّرات (مرة واحدة فقط — لا يلمس مديراً موجوداً)
if [ -n "${ADMIN_EMAIL}" ]; then
    php artisan app:make-admin --bootstrap || true
fi

# إضافة/تحديث مجموعة الأفلام (آمن للتكرار: ينشئ الناقص ويملأ الفارغ فقط)
php artisan db:seed --class=FilmsSeeder --force || true

# تحسين الأداء للإنتاج (كاش الإعدادات والمسارات والواجهات)
php artisan config:cache
php artisan route:cache
php artisan view:cache

exec "$@"
