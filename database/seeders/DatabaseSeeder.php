<?php

namespace Database\Seeders;

use App\Models\Genre;
use App\Models\Review;
use App\Models\Title;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // مستخدمين تجريبيين
        $admin = User::factory()->create([
            'name' => 'أحمد',
            'email' => 'ahmed@example.com',
            'role' => User::ROLE_ADMIN,
        ]);

        $sara = User::factory()->create([
            'name' => 'سارة',
            'email' => 'sara@example.com',
            'role' => User::ROLE_EDITOR,
        ]);

        $users = User::factory(5)->create()->push($admin, $sara);

        // التصنيفات
        $genreNames = ['أكشن', 'دراما', 'كوميدي', 'رعب', 'خيال علمي', 'جريمة', 'رومانسي', 'مغامرة'];
        $genres = collect($genreNames)->mapWithKeys(function (string $name) {
            $genre = Genre::create(['name' => $name, 'slug' => Str::slug($name) ?: Str::random(6)]);
            return [$name => $genre];
        });

        // الأعمال (أفلام ومسلسلات)
        $titles = [
            ['name' => 'البداية', 'type' => 'movie', 'genre' => 'خيال علمي', 'year' => 2010, 'desc' => 'لص يسرق أسرار الشركات عبر تقنية اقتحام الأحلام، يُكلَّف بمهمة عكسية: زرع فكرة في عقل هدف.'],
            ['name' => 'الأب الروحي', 'type' => 'movie', 'genre' => 'جريمة', 'year' => 1972, 'desc' => 'ملحمة عن عائلة مافيا إيطالية في أمريكا وانتقال السلطة من الأب إلى أصغر أبنائه.'],
            ['name' => 'الفارس الأسود', 'type' => 'movie', 'genre' => 'أكشن', 'year' => 2008, 'desc' => 'بطل المدينة المقنّع يواجه مجرماً فوضوياً يهدّد المدينة بالكامل في صراع نفسي عنيف.'],
            ['name' => 'بين النجوم', 'type' => 'movie', 'genre' => 'خيال علمي', 'year' => 2014, 'desc' => 'فريق من روّاد الفضاء يعبرون ثقباً دودياً بحثاً عن موطن جديد للبشرية.'],
            ['name' => 'الصدمة', 'type' => 'movie', 'genre' => 'دراما', 'year' => 1994, 'desc' => 'قصة أمل وصداقة بين سجينين داخل سجن صارم على مدى عقود.'],
            ['name' => 'العرّاب الأخير', 'type' => 'movie', 'genre' => 'رعب', 'year' => 2019, 'desc' => 'عائلة تنتقل لمنزل ريفي قديم لتكتشف أنه يخفي ماضياً مرعباً.'],

            ['name' => 'اللعبة الكبرى', 'type' => 'series', 'genre' => 'دراما', 'year' => 2011, 'desc' => 'صراع عائلات نبيلة على عرش مملكة خيالية وسط مؤامرات وخيانات وتنانين.'],
            ['name' => 'الكسر', 'type' => 'series', 'genre' => 'جريمة', 'year' => 2008, 'desc' => 'مدرّس كيمياء يتحوّل إلى مصنّع مخدرات بعد تشخيصه بمرض خطير.'],
            ['name' => 'الأشياء الغريبة', 'type' => 'series', 'genre' => 'خيال علمي', 'year' => 2016, 'desc' => 'مجموعة أطفال في بلدة صغيرة يواجهون قوى خارقة وأبعاداً مظلمة في الثمانينات.'],
            ['name' => 'التاج', 'type' => 'series', 'genre' => 'دراما', 'year' => 2016, 'desc' => 'سيرة درامية عن حكم ملكة وصراعاتها السياسية والشخصية عبر العقود.'],
            ['name' => 'مكتب التحقيقات', 'type' => 'series', 'genre' => 'كوميدي', 'year' => 2005, 'desc' => 'كوميديا توثيقية عن حياة موظفي مكتب مبيعات عادي بمواقف طريفة.'],
            ['name' => 'الميت السائر', 'type' => 'series', 'genre' => 'رعب', 'year' => 2010, 'desc' => 'مجموعة ناجين يحاولون البقاء في عالم اجتاحته الزومبي.'],
        ];

        $posters = [
            '0f1a3e', '1b2a4c', '2c3b5d', '3d4c6e', '4e5d7f', '5f6e80',
            '6a7f91', '7b80a2', '8c91b3', '9da2c4', 'aeb3d5', 'bfc4e6',
        ];

        foreach ($titles as $i => $data) {
            $title = Title::create([
                'genre_id' => $genres[$data['genre']]->id,
                'name' => $data['name'],
                'type' => $data['type'],
                'description' => $data['desc'],
                'release_year' => $data['year'],
                'poster' => 'https://placehold.co/400x600/' . $posters[$i] . '/ffffff?text=' . urlencode($data['name']),
            ]);

            // مراجعات عشوائية لكل عمل
            $reviewers = $users->random(rand(2, 5));
            foreach ($reviewers as $user) {
                Review::create([
                    'user_id' => $user->id,
                    'title_id' => $title->id,
                    'rating' => rand(3, 5),
                    'body' => collect([
                        'عمل رائع يستحق المشاهدة، الإخراج والأداء ممتازان.',
                        'قصة مشوّقة لكن النهاية كانت متوقعة بعض الشيء.',
                        'من أفضل ما شاهدت هذا العام، أنصح فيه بشدة.',
                        'جيد بشكل عام، فيه لحظات مملة لكنه يستحق.',
                        'تحفة فنية حقيقية، كل تفصيلة فيه مدروسة.',
                    ])->random(),
                ]);
            }
        }
    }
}
