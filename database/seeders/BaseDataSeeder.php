<?php

namespace Database\Seeders;

use App\Models\Genre;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BaseDataSeeder extends Seeder
{
    /**
     * تصنيفات ووسوم افتراضية — مرة واحدة فقط (لا تُعاد إن كانت موجودة).
     */
    public function run(): void
    {
        if (Genre::query()->doesntExist()) {
            $genres = [
                'أكشن', 'دراما', 'كوميدي', 'رعب', 'خيال علمي', 'جريمة', 'رومانسي',
                'مغامرة', 'إثارة', 'تاريخي', 'حربي', 'غموض', 'فانتازيا', 'أنيميشن', 'وثائقي',
            ];
            foreach ($genres as $name) {
                Genre::create(['name' => $name, 'slug' => Str::slug($name) ?: Str::random(8)]);
            }
        }

        if (Tag::query()->doesntExist()) {
            $tags = ['كلاسيكي', 'حائز جوائز', 'مبني على قصة حقيقية', 'عائلي', 'تشويق', 'ملحمي'];
            foreach ($tags as $name) {
                Tag::create(['name' => $name, 'slug' => Str::slug($name) ?: Str::random(8)]);
            }
        }
    }
}
