<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

#[Signature('app:test-storage {--keep : إبقاء الملف التجريبي بدل حذفه}')]
#[Description('فحص قرص تخزين البوسترات (محلي أو سحابي R2/S3) ورفع ملف تجريبي')]
class TestStorage extends Command
{
    public function handle(): int
    {
        $disk = config('filesystems.poster_disk', 'public');
        $this->line("قرص البوسترات الحالي: <comment>{$disk}</comment>");

        if ($disk === 'public') {
            $this->warn('القرص محلي (مؤقت على Render). فعّل R2 بضبط POSTER_DISK=s3 لتخزين دائم.');
        }

        $path = 'storage-test/test-' . now()->format('YmdHis') . '.txt';
        $content = 'MJCOZ TV storage test @ ' . now()->toDateTimeString();

        try {
            Storage::disk($disk)->put($path, $content, 'public');

            if (! Storage::disk($disk)->exists($path)) {
                $this->error('فشل: الملف لم يُكتب على القرص.');
                return self::FAILURE;
            }

            $url = Storage::disk($disk)->url($path);

            $this->info('✓ الاتصال بالتخزين يعمل بنجاح.');
            $this->line('رابط الملف التجريبي:');
            $this->line("<info>{$url}</info>");
            $this->line('افتح الرابط في المتصفح — لو فتح المحتوى فالتخزين العام مضبوط ✓');

            if (! $this->option('keep')) {
                Storage::disk($disk)->delete($path);
                $this->line('(تم حذف الملف التجريبي — استخدم --keep لإبقائه)');
            }

            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('فشل الاتصال بالتخزين: ' . $e->getMessage());
            $this->line('تحقّق من متغيّرات AWS_* و POSTER_DISK في الإعدادات.');
            return self::FAILURE;
        }
    }
}
