<?php

namespace Database\Seeders;

use App\Models\Genre;
use App\Models\Review;
use App\Models\Tag;
use App\Models\Title;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // أمان: لا تُعد البذر إذا كانت قاعدة البيانات معبّأة (مفيد عند النشر المتكرر)
        if (User::query()->exists()) {
            return;
        }

        // حساب المدير — يمكن ضبطه عبر متغيّرات البيئة عند النشر
        $admin = User::create([
            'name' => 'أحمد',
            'email' => env('ADMIN_EMAIL', 'ahmed@example.com'),
            'password' => Hash::make(env('ADMIN_PASSWORD', 'password')),
            'role' => User::ROLE_ADMIN,
        ]);

        $sara = User::create([
            'name' => 'سارة',
            'email' => 'sara@example.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_EDITOR,
        ]);

        // مستخدمون عاديّون للبيانات التجريبية (بدون Faker ليعمل في الإنتاج)
        $names = ['نورة', 'خالد', 'ريم', 'فهد', 'لمى'];
        $extra = collect($names)->map(fn (string $name, int $i) => User::create([
            'name' => $name,
            'email' => 'user' . ($i + 1) . '@example.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_USER,
        ]));

        $users = $extra->push($admin, $sara);

        // التصنيفات
        $genreNames = ['أكشن', 'دراما', 'كوميدي', 'رعب', 'خيال علمي', 'جريمة', 'رومانسي', 'مغامرة'];
        $genres = collect($genreNames)->mapWithKeys(function (string $name) {
            $genre = Genre::create(['name' => $name, 'slug' => Str::slug($name) ?: Str::random(6)]);
            return [$name => $genre];
        });

        // الوسوم
        $tagNames = ['كلاسيكي', 'حائز جوائز', 'مبني على قصة حقيقية', 'إثارة', 'عائلي', 'تشويق', 'ملحمي', 'مرشّح للأوسكار'];
        $tags = collect($tagNames)->map(fn ($name, $i) => Tag::create([
            'name' => $name,
            'slug' => Str::slug($name) ?: 'tag-' . $i,
        ]));

        // الأعمال (أفلام ومسلسلات) — مع تقييمات و"شاهدتها" لبعضها
        $titles = [
            ['name' => 'البداية', 'type' => 'movie', 'genre' => 'خيال علمي', 'year' => 2010, 'desc' => 'لص يسرق أسرار الشركات عبر تقنية اقتحام الأحلام، يُكلَّف بمهمة عكسية: زرع فكرة في عقل هدف.', 'imdb' => 8.8, 'rt' => 87, 'personal' => 9, 'watched' => true],
            ['name' => 'الأب الروحي', 'type' => 'movie', 'genre' => 'جريمة', 'year' => 1972, 'desc' => 'ملحمة عن عائلة مافيا إيطالية في أمريكا وانتقال السلطة من الأب إلى أصغر أبنائه.', 'imdb' => 9.2, 'rt' => 97, 'personal' => 10, 'watched' => true],
            ['name' => 'الفارس الأسود', 'type' => 'movie', 'genre' => 'أكشن', 'year' => 2008, 'desc' => 'بطل المدينة المقنّع يواجه مجرماً فوضوياً يهدّد المدينة بالكامل في صراع نفسي عنيف.', 'imdb' => 9.0, 'rt' => 94, 'personal' => 9, 'watched' => true],
            ['name' => 'بين النجوم', 'type' => 'movie', 'genre' => 'خيال علمي', 'year' => 2014, 'desc' => 'فريق من روّاد الفضاء يعبرون ثقباً دودياً بحثاً عن موطن جديد للبشرية.', 'imdb' => 8.7, 'rt' => 73, 'personal' => 8, 'watched' => true],
            ['name' => 'الصدمة', 'type' => 'movie', 'genre' => 'دراما', 'year' => 1994, 'desc' => 'قصة أمل وصداقة بين سجينين داخل سجن صارم على مدى عقود.', 'imdb' => 9.3, 'rt' => 89, 'personal' => 10, 'watched' => true],
            ['name' => 'العرّاب الأخير', 'type' => 'movie', 'genre' => 'رعب', 'year' => 2019, 'desc' => 'عائلة تنتقل لمنزل ريفي قديم لتكتشف أنه يخفي ماضياً مرعباً.'],

            ['name' => 'اللعبة الكبرى', 'type' => 'series', 'genre' => 'دراما', 'year' => 2011, 'desc' => 'صراع عائلات نبيلة على عرش مملكة خيالية وسط مؤامرات وخيانات وتنانين.', 'imdb' => 9.2, 'rt' => 89, 'personal' => 9, 'watched' => true],
            ['name' => 'الكسر', 'type' => 'series', 'genre' => 'جريمة', 'year' => 2008, 'desc' => 'مدرّس كيمياء يتحوّل إلى مصنّع مخدرات بعد تشخيصه بمرض خطير.', 'imdb' => 9.5, 'rt' => 96, 'personal' => 10, 'watched' => true],
            ['name' => 'الأشياء الغريبة', 'type' => 'series', 'genre' => 'خيال علمي', 'year' => 2016, 'desc' => 'مجموعة أطفال في بلدة صغيرة يواجهون قوى خارقة وأبعاداً مظلمة في الثمانينات.', 'imdb' => 8.7, 'rt' => 92, 'personal' => 8, 'watched' => true],
            ['name' => 'التاج', 'type' => 'series', 'genre' => 'دراما', 'year' => 2016, 'desc' => 'سيرة درامية عن حكم ملكة وصراعاتها السياسية والشخصية عبر العقود.'],
            ['name' => 'مكتب التحقيقات', 'type' => 'series', 'genre' => 'كوميدي', 'year' => 2005, 'desc' => 'كوميديا توثيقية عن حياة موظفي مكتب مبيعات عادي بمواقف طريفة.', 'imdb' => 9.0, 'rt' => 81, 'personal' => 9, 'watched' => true],
            ['name' => 'الميت السائر', 'type' => 'series', 'genre' => 'رعب', 'year' => 2010, 'desc' => 'مجموعة ناجين يحاولون البقاء في عالم اجتاحته الزومبي.'],
        ];

        $comments = [
            'اتفق معك تماماً!', 'وجهة نظر جميلة.', 'ما أعجبني بقدرك بس محترم.',
            'صح لسانك، من أفضل الأعمال.', 'النهاية خذلتني بصراحة.', 'شكراً على المراجعة المفيدة.',
        ];

        foreach ($titles as $data) {
            $title = Title::create([
                'genre_id' => $genres[$data['genre']]->id,
                'name' => $data['name'],
                'type' => $data['type'],
                'description' => $data['desc'],
                'release_year' => $data['year'],
                'poster' => null, // يُستخدم البوستر المولّد تلقائياً
                'imdb_rating' => $data['imdb'] ?? null,
                'rt_rating' => $data['rt'] ?? null,
                'personal_rating' => $data['personal'] ?? null,
                'watched' => $data['watched'] ?? false,
                'watched_at' => isset($data['watched']) ? now()->subDays(rand(1, 120)) : null,
            ]);

            // وسوم عشوائية لكل عمل
            $title->tags()->attach($tags->random(rand(1, 3))->pluck('id')->all());

            // مراجعات عشوائية لكل عمل
            $reviewers = $users->random(rand(2, 5));
            foreach ($reviewers as $user) {
                $review = Review::create([
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

                // ردود وإعجابات من مستخدمين آخرين
                foreach ($users->where('id', '!=', $user->id)->random(rand(0, 3)) as $other) {
                    if (rand(0, 1)) {
                        $review->comments()->create([
                            'user_id' => $other->id,
                            'body' => collect($comments)->random(),
                        ]);
                    }
                    $review->likes()->create([
                        'user_id' => $other->id,
                        'helpful' => (bool) rand(0, 1),
                    ]);
                }
            }
        }
    }
}
