<?php

namespace App\Console\Commands;

use App\Models\Title;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

#[Signature('app:fetch-posters {--force : إعادة الجلب حتى لو عند العمل بوستر بالفعل}')]
#[Description('جلب بوسترات الأفلام من ويكيبيديا (مصدر مجاني بدون مفتاح)')]
class FetchPosters extends Command
{
    private const UA = 'MJCOZ-TV/1.0 (movie review site)';

    public function handle(): int
    {
        $query = Title::query();
        if (! $this->option('force')) {
            // الأعمال التي ليس لها بوستر خارجي بعد
            $query->where(fn ($q) => $q->whereNull('poster')->orWhere('poster', 'not like', 'http%'));
        }
        $titles = $query->get();

        $this->info("معالجة {$titles->count()} عمل...");
        $ok = 0;

        foreach ($titles as $title) {
            // نتجاهل الأسماء غير اللاتينية (مصدر ويكيبيديا الإنجليزي غير دقيق لها)
            if (! preg_match('/[A-Za-z]/', $title->name)) {
                continue;
            }

            $poster = $this->fetchPoster($title->name, $title->release_year);

            if ($poster) {
                $title->update(['poster' => $poster]);
                $ok++;
                $this->line("✓ {$title->name}");
            } else {
                $this->warn("… لم يُعثر على بوستر: {$title->name}");
            }

            usleep(300_000); // احترام حدود الخدمة
        }

        $this->info("تم تحديث {$ok} بوستر.");

        return self::SUCCESS;
    }

    /**
     * إيجاد رابط بوستر العمل من ويكيبيديا.
     */
    private function fetchPoster(string $name, ?int $year): ?string
    {
        // عناوين مرشّحة (الأدق أولاً)، ثم البحث كحل أخير
        $candidates = array_filter([
            $year ? "{$name} ({$year} film)" : null,
            "{$name} (film)",
            $name,
        ]);

        foreach ($candidates as $candidate) {
            if ($img = $this->imageFromPage($candidate)) {
                return $img;
            }
        }

        // حل أخير: البحث
        $res = $this->get('https://en.wikipedia.org/w/api.php', [
            'action' => 'query', 'list' => 'search', 'format' => 'json',
            'srsearch' => trim("{$name} film"), 'srlimit' => 1,
        ]);
        $hit = $res?->json('query.search.0.title');

        return $hit ? $this->imageFromPage($hit) : null;
    }

    /**
     * جلب الصورة الرئيسية لصفحة ويكيبيديا (تجاهل صفحات التوضيح).
     */
    private function imageFromPage(string $pageTitle): ?string
    {
        $res = $this->get('https://en.wikipedia.org/api/rest_v1/page/summary/' . rawurlencode($pageTitle));

        if (! $res || ! $res->ok() || $res->json('type') === 'disambiguation') {
            return null;
        }

        return $res->json('originalimage.source') ?? $res->json('thumbnail.source');
    }

    /**
     * طلب GET مع إعادة محاولة عند تجاوز الحد (429) أو أخطاء الخادم.
     */
    private function get(string $url, array $query = [])
    {
        for ($attempt = 1; $attempt <= 4; $attempt++) {
            try {
                $res = Http::withHeaders(['User-Agent' => self::UA])->timeout(20)->get($url, $query);

                if ($res->status() === 429 || $res->serverError()) {
                    usleep(($attempt * 800_000)); // تباطؤ تصاعدي
                    continue;
                }

                return $res;
            } catch (\Throwable $e) {
                usleep($attempt * 800_000);
            }
        }

        return null;
    }
}
