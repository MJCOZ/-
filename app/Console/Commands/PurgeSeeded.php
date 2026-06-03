<?php

namespace App\Console\Commands;

use App\Models\Title;
use Database\Seeders\CatalogSeeder;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:purge-seeded {--all : حذف كل الأعمال بدون استثناء}')]
#[Description('حذف الأعمال التي أضافها النظام تلقائياً (تبقى الأعمال التي أضفتها أنت)')]
class PurgeSeeded extends Command
{
    public function handle(): int
    {
        if ($this->option('all')) {
            $count = Title::count();
            Title::query()->delete();
            $this->info("تم حذف كل الأعمال ({$count}).");
            return self::SUCCESS;
        }

        $names = collect((new CatalogSeeder)->items())->pluck('name')
            ->merge($this->filmNames())
            ->merge($this->demoNames())
            ->unique()
            ->all();

        $deleted = Title::whereIn('name', $names)->count();
        Title::whereIn('name', $names)->delete(); // الحذف يشمل المراجعات والتعليقات تلقائياً (cascade)

        $this->info("تم حذف {$deleted} عمل أضافها النظام. الأعمال التي أضفتها أنت بقيت كما هي.");

        return self::SUCCESS;
    }

    /** أسماء الأفلام الستة عشر (FilmsSeeder). */
    private function filmNames(): array
    {
        return [
            'The Gorge', 'Obsession', 'I Swear', 'Predestination', 'Her', 'The King',
            'Pulp Fiction', 'Forrest Gump', 'The Matrix', 'Gladiator', 'Parasite',
            'Whiplash', 'The Prestige', 'Joker', 'Spirited Away', 'Mad Max: Fury Road',
        ];
    }

    /** الأعمال التجريبية الأولى (DatabaseSeeder). */
    private function demoNames(): array
    {
        return [
            'البداية', 'الأب الروحي', 'الفارس الأسود', 'بين النجوم', 'الصدمة', 'العرّاب الأخير',
            'اللعبة الكبرى', 'الكسر', 'الأشياء الغريبة', 'التاج', 'مكتب التحقيقات', 'الميت السائر',
        ];
    }
}
