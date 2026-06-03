<?php

namespace App\Console\Commands;

use App\Models\Title;
use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

#[Signature('app:db-info')]
#[Description('عرض نوع قاعدة البيانات المستخدمة ومعلوماتها (للتأكد من الثبات)')]
class DbInfo extends Command
{
    public function handle(): int
    {
        $driver = DB::connection()->getDriverName();
        $database = DB::connection()->getDatabaseName();

        $this->line('نوع قاعدة البيانات: <comment>' . $driver . '</comment>');
        $this->line('الاسم/المسار: <comment>' . $database . '</comment>');
        $this->line('عدد الأعمال: <comment>' . Title::count() . '</comment> | المستخدمون: <comment>' . User::count() . '</comment>');

        if ($driver === 'sqlite') {
            $this->newLine();
            $this->error('⚠️ قاعدة SQLite مؤقتة على Render — بياناتك تُمسح عند كل نشر/إعادة تشغيل!');
            $this->warn('الحل: اربط PostgreSQL دائمة (DB_CONNECTION=pgsql + بيانات قاعدة Render).');
        } else {
            $this->newLine();
            $this->info('✓ قاعدة بيانات دائمة — بياناتك آمنة وتبقى بعد النشر.');
        }

        return self::SUCCESS;
    }
}
