<?php

namespace Tests\Feature;

use App\Models\Genre;
use App\Models\Title;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_loads(): void
    {
        Title::factory()->count(3)->create();

        $this->get('/')->assertOk()->assertSee('CineReview');
    }

    public function test_titles_index_lists_titles(): void
    {
        $title = Title::factory()->create(['name' => 'فيلم الاختبار']);

        $this->get('/titles')->assertOk()->assertSee('فيلم الاختبار');
    }

    public function test_titles_can_be_searched_by_name(): void
    {
        Title::factory()->create(['name' => 'البحث الفريد']);
        Title::factory()->create(['name' => 'عمل آخر']);

        $this->get('/titles?q=الفريد')
            ->assertOk()
            ->assertSee('البحث الفريد')
            ->assertDontSee('عمل آخر');
    }

    public function test_titles_can_be_filtered_by_type(): void
    {
        Title::factory()->movie()->create(['name' => 'فيلمي']);
        Title::factory()->series()->create(['name' => 'مسلسلي']);

        $this->get('/titles?type=movie')
            ->assertOk()
            ->assertSee('فيلمي')
            ->assertDontSee('مسلسلي');
    }

    public function test_title_detail_page_loads(): void
    {
        $title = Title::factory()->create(['name' => 'تفاصيل العمل']);

        $this->get("/titles/{$title->id}")->assertOk()->assertSee('تفاصيل العمل');
    }

    public function test_genre_page_shows_its_titles(): void
    {
        $genre = Genre::factory()->create(['name' => 'تصنيفي']);
        Title::factory()->create(['genre_id' => $genre->id, 'name' => 'عمل ضمن التصنيف']);

        $this->get("/genres/{$genre->id}")
            ->assertOk()
            ->assertSee('عمل ضمن التصنيف');
    }

    public function test_watched_page_only_shows_watched_titles(): void
    {
        Title::factory()->watched()->create(['name' => 'عمل مُشاهَد']);
        Title::factory()->create(['name' => 'عمل غير مُشاهَد']);

        $this->get('/watched')
            ->assertOk()
            ->assertSee('عمل مُشاهَد')
            ->assertDontSee('عمل غير مُشاهَد');
    }

    public function test_poster_route_returns_svg(): void
    {
        $title = Title::factory()->create();

        $this->get("/titles/{$title->id}/poster")
            ->assertOk()
            ->assertHeader('Content-Type', 'image/svg+xml');
    }
}
