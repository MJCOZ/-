<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // ترقيم صفحات بنمط Bootstrap 5 (أرقام) بدل النمط الافتراضي
        Paginator::useBootstrapFive();

        // خلف بروكسي HTTPS (مثل Render): اجبر توليد الروابط على https
        // لتفادي حظر ملفات CSS/الخطوط بسبب mixed content.
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
