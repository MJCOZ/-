# دليل نشر موقع CineReview 🚀

هذا الدليل يشرح طرق نشر الموقع على سيرفر حقيقي مع رابط عام.

## ✅ قائمة التحقق قبل النشر (الإنتاج)

في ملف `.env` على السيرفر:

```env
APP_NAME=CineReview
APP_ENV=production
APP_DEBUG=false
APP_KEY=            # ولّده: php artisan key:generate --show
APP_URL=https://your-domain.com
APP_LOCALE=ar

# الأبسط: SQLite
DB_CONNECTION=sqlite
DB_DATABASE=/var/www/html/database/database.sqlite

# أو MySQL:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=cinereview
# DB_USERNAME=cinereview
# DB_PASSWORD=********
```

أوامر ما بعد النشر:
```bash
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan storage:link
php artisan config:cache && php artisan route:cache && php artisan view:cache
```

> مهم: `APP_DEBUG=false` و `php artisan storage:link` (لعرض البوسترات المرفوعة).

---

## 🐳 الطريقة 1: Docker (موصى بها — أسهل)

كل شيء جاهز (`Dockerfile`, `docker-compose.yml`, `docker/entrypoint.sh`).

```bash
# 1) ولّد مفتاح التطبيق مرة واحدة
docker run --rm -v "$PWD":/app -w /app composer:2 sh -c "composer install -q && php artisan key:generate --show"
# انسخ القيمة وضعها في متغيّر APP_KEY (أو ملف .env)

# 2) شغّل
APP_KEY="base64:..." docker compose up -d --build

# الموقع على:  http://localhost:8080
```

الحاوية تشغّل تلقائياً: الهجرات + ربط التخزين + كاش الإنتاج. قاعدة البيانات والملفات المرفوعة محفوظة في volumes.

لإضافة دومين و HTTPS: ضع Nginx أو Caddy كـ reverse proxy أمام المنفذ 8080، أو استخدم Traefik.

---

## 🖥️ الطريقة 2: سيرفر VPS (Nginx + PHP-FPM)

```bash
# المتطلبات: PHP 8.4 + إضافاته، Composer، Nginx
sudo apt install php8.4-fpm php8.4-sqlite3 php8.4-mbstring php8.4-xml php8.4-gd php8.4-intl php8.4-bcmath php8.4-zip nginx -y

git clone <repo> /var/www/cinereview && cd /var/www/cinereview
composer install --no-dev --optimize-autoloader
cp .env.example .env && php artisan key:generate
touch database/database.sqlite
php artisan migrate --force && php artisan storage:link
php artisan config:cache && php artisan route:cache && php artisan view:cache
sudo chown -R www-data:www-data storage bootstrap/cache database
```

إعداد Nginx (`/etc/nginx/sites-available/cinereview`):
```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /var/www/cinereview/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php8.4-fpm.sock;
    }

    location ~ /\.(?!well-known).* { deny all; }
}
```
ثم: `sudo ln -s ... /etc/nginx/sites-enabled/ && sudo nginx -t && sudo systemctl reload nginx`
و للـ HTTPS مجاناً: `sudo certbot --nginx -d your-domain.com`

---

## ☁️ الطريقة 3: منصات مُدارة (الأسرع لرابط عام)

- **Railway / Render / Fly.io**: تدعم `Dockerfile` مباشرة — اربط مستودع GitHub، عيّن متغيّرات البيئة (`APP_KEY`, `APP_URL`...)، وانشر.
- **Laravel Cloud / Laravel Forge**: مخصّصة لـ Laravel، تربط المستودع وتدير الخادم نيابةً عنك.

في كل الحالات: عيّن `APP_KEY` و `APP_URL`، واترك بقية الأوامر للـ entrypoint أو لخطوة build.

---

## 🔄 التكامل المستمر (CI)

ملف `.github/workflows/ci.yml` يشغّل **كل الاختبارات تلقائياً** على كل push و pull request،
فتضمن أن أي تعديل لا يكسر الموقع قبل النشر.
