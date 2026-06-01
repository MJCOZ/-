<?php

namespace Database\Seeders;

use App\Models\Genre;
use App\Models\Title;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FilmsSeeder extends Seeder
{
    /**
     * إضافة مجموعة أفلام (قابلة لإعادة التشغيل بأمان — لا تُكرّر).
     */
    public function run(): void
    {
        $films = [
            // ===== أفلام طلبها المستخدم (تواريخ/تقييمات بعضها تقريبية للتعديل) =====
            ['name' => 'The Gorge', 'poster' => 'https://upload.wikimedia.org/wikipedia/en/d/dc/The_Gorge_%28film%29_poster.jpg', 'year' => 2025, 'imdb' => 7.0, 'rt' => 73, 'genres' => ['خيال علمي', 'أكشن', 'رومانسي'],
                'desc' => 'قنّاصان يحرسان وادياً غامضاً من جهتين متقابلتين، تنشأ بينهما علاقة بينما يكتشفان سرّاً مرعباً مدفوناً في الأعماق.'],
            ['name' => 'Obsession', 'poster' => 'https://upload.wikimedia.org/wikipedia/en/0/05/Obsession_theatrical_poster.jpeg', 'year' => 2025, 'imdb' => 5.6, 'rt' => 40, 'genres' => ['دراما', 'إثارة'],
                'desc' => 'دراما إثارة نفسية عن علاقة محمومة تتحوّل تدريجياً إلى هوس خطير يقلب حياة الطرفين رأساً على عقب.'],
            ['name' => 'I Swear', 'poster' => 'https://upload.wikimedia.org/wikipedia/en/5/57/I_Swear_film_poster.jpg', 'year' => 2025, 'imdb' => 7.2, 'rt' => 80, 'genres' => ['دراما'],
                'desc' => 'دراما ملهمة مبنية على قصة واقعية عن شخص يتحدّى الصعاب والتحديات ليثبت نفسه ويحقق حلمه.'],
            ['name' => 'Predestination', 'poster' => 'https://upload.wikimedia.org/wikipedia/en/4/4b/Predestination_poster.jpg', 'year' => 2014, 'imdb' => 7.5, 'rt' => 84, 'genres' => ['خيال علمي', 'إثارة', 'دراما'],
                'desc' => 'عميل زمني يخوض مهمته الأخيرة لإيقاف مجرم هارب عبر الزمن، في حبكة سفر زمني مذهلة ومتشابكة.'],
            ['name' => 'Her', 'poster' => 'https://upload.wikimedia.org/wikipedia/en/4/44/Her2013Poster.jpg', 'year' => 2013, 'imdb' => 8.0, 'rt' => 94, 'genres' => ['دراما', 'رومانسي', 'خيال علمي'],
                'desc' => 'في مستقبل قريب، يقع كاتب وحيد في حب نظام تشغيل ذكي بصوت آسر، فيخوض علاقة عاطفية غير تقليدية.'],
            ['name' => 'The King', 'poster' => 'https://upload.wikimedia.org/wikipedia/en/2/24/The_King_poster.jpeg', 'year' => 2019, 'imdb' => 7.2, 'rt' => 71, 'genres' => ['دراما', 'أكشن'],
                'desc' => 'الأمير الشاب «هال» يعتلي عرش إنجلترا ملكاً، فيواجه أعباء الحكم والمؤامرات والحرب وإرث والده الثقيل.'],

            // ===== عشرة أفلام مختارة (كلاسيكيات بتقييمات دقيقة) =====
            ['name' => 'Pulp Fiction', 'poster' => 'https://upload.wikimedia.org/wikipedia/en/3/3b/Pulp_Fiction_%281994%29_poster.jpg', 'year' => 1994, 'imdb' => 8.9, 'rt' => 92, 'genres' => ['جريمة', 'دراما'],
                'desc' => 'قصص متشابكة من عالم الجريمة في لوس أنجلوس تُروى بأسلوب غير خطّي ساخر وعنيف، من إخراج تارانتينو.'],
            ['name' => 'Forrest Gump', 'poster' => 'https://upload.wikimedia.org/wikipedia/en/6/67/Forrest_Gump_poster.jpg', 'year' => 1994, 'imdb' => 8.8, 'rt' => 74, 'genres' => ['دراما', 'رومانسي'],
                'desc' => 'رجل بسيط الطباع يعيش - دون أن يقصد - أبرز أحداث القرن العشرين، بينما يبقى قلبه معلّقاً بحبّه الأول.'],
            ['name' => 'The Matrix', 'poster' => 'https://upload.wikimedia.org/wikipedia/en/d/db/The_Matrix.png', 'year' => 1999, 'imdb' => 8.7, 'rt' => 83, 'genres' => ['خيال علمي', 'أكشن'],
                'desc' => 'مبرمج يكتشف أن العالم الذي يعيشه مجرد محاكاة، فينضم لثورة ضد الآلات التي تستعبد البشرية.'],
            ['name' => 'Gladiator', 'poster' => 'https://upload.wikimedia.org/wikipedia/en/f/fb/Gladiator_%282000_film_poster%29.png', 'year' => 2000, 'imdb' => 8.5, 'rt' => 80, 'genres' => ['أكشن', 'دراما', 'مغامرة'],
                'desc' => 'جنرال روماني يُخان ويُستعبد، فيصعد كمصارع في الحلبة لينتقم من الإمبراطور الذي قتل عائلته.'],
            ['name' => 'Parasite', 'poster' => 'https://upload.wikimedia.org/wikipedia/en/5/53/Parasite_%282019_film%29.png', 'year' => 2019, 'imdb' => 8.5, 'rt' => 99, 'genres' => ['دراما', 'إثارة', 'كوميدي'],
                'desc' => 'عائلة فقيرة تتسلّل بذكاء إلى خدمة عائلة ثرية، فتتصاعد الأحداث نحو نهاية صادمة. الفائز بأوسكار أفضل فيلم.'],
            ['name' => 'Whiplash', 'poster' => 'https://upload.wikimedia.org/wikipedia/en/0/01/Whiplash_poster.jpg', 'year' => 2014, 'imdb' => 8.5, 'rt' => 94, 'genres' => ['دراما'],
                'desc' => 'عازف درامز طموح يخضع لتدريب أستاذ قاسٍ لا يرحم، في صراع محموم بين الموهبة والكمال والجنون.'],
            ['name' => 'The Prestige', 'poster' => 'https://upload.wikimedia.org/wikipedia/en/d/d2/Prestige_poster.jpg', 'year' => 2006, 'imdb' => 8.5, 'rt' => 76, 'genres' => ['خيال علمي', 'إثارة', 'دراما'],
                'desc' => 'ساحران متنافسان يتبادلان الخدع والانتقام بحثاً عن الحيلة المثالية، حتى تتحوّل المنافسة إلى هوس مدمّر.'],
            ['name' => 'Joker', 'poster' => 'https://upload.wikimedia.org/wikipedia/en/e/e1/Joker_%282019_film%29_poster.jpg', 'year' => 2019, 'imdb' => 8.4, 'rt' => 68, 'genres' => ['جريمة', 'دراما', 'إثارة'],
                'desc' => 'كوميدي فاشل ومهمّش تدفعه قسوة المجتمع نحو الجنون، ليتحوّل إلى رمز للفوضى في مدينة جوثام.'],
            ['name' => 'Spirited Away', 'poster' => 'https://upload.wikimedia.org/wikipedia/en/d/db/Spirited_Away_Japanese_poster.png', 'year' => 2001, 'imdb' => 8.6, 'rt' => 97, 'genres' => ['مغامرة', 'خيال علمي'],
                'desc' => 'طفلة تتيه في عالم أرواح ساحر، فتخوض رحلة لإنقاذ والديها والعودة إلى عالمها. تحفة أنمي من استوديو جيبلي.'],
            ['name' => 'Mad Max: Fury Road', 'poster' => 'https://upload.wikimedia.org/wikipedia/en/6/6e/Mad_Max_Fury_Road.jpg', 'year' => 2015, 'imdb' => 8.1, 'rt' => 97, 'genres' => ['أكشن', 'مغامرة', 'خيال علمي'],
                'desc' => 'في عالم صحراوي ما بعد كارثي، يتحالف هارب وقائدة متمرّدة في مطاردة سيارات جنونية هرباً من طاغية.'],
        ];

        foreach ($films as $data) {
            // ينشئ الفيلم إن لم يوجد (مع كل البيانات)، ولا يلمس الموجود
            $title = Title::firstOrCreate(
                ['name' => $data['name']],
                [
                    'type' => 'movie',
                    'description' => $data['desc'],
                    'release_year' => $data['year'],
                    'imdb_rating' => $data['imdb'],
                    'rt_rating' => $data['rt'],
                    'poster' => $data['poster'] ?? null,
                ],
            );

            // للأفلام الموجودة مسبقاً: نملأ الحقول الفارغة فقط (نحافظ على تعديلاتك)
            $fill = [];
            if (blank($title->poster)) {
                $fill['poster'] = $data['poster'] ?? null;
            }
            if (is_null($title->imdb_rating)) {
                $fill['imdb_rating'] = $data['imdb'];
            }
            if (is_null($title->rt_rating)) {
                $fill['rt_rating'] = $data['rt'];
            }
            if (blank($title->description)) {
                $fill['description'] = $data['desc'];
            }
            if ($fill) {
                $title->update($fill);
            }

            // ربط التصنيفات (إضافة دون حذف الموجود)
            $genreIds = collect($data['genres'])->map(function (string $name) {
                return Genre::firstOrCreate(
                    ['name' => $name],
                    ['slug' => Str::slug($name) ?: Str::random(8)],
                )->id;
            })->all();

            $title->genres()->syncWithoutDetaching($genreIds);
            if (! $title->genre_id) {
                $title->update(['genre_id' => $genreIds[0] ?? null]);
            }
        }

        $this->command?->info('تمت إضافة/تحديث ' . count($films) . ' فيلم.');
    }
}
