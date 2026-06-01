<?php

namespace Tests\Feature;

use App\Models\Genre;
use App\Models\Review;
use App\Models\Title;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecommendationTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_recommends_titles_from_genres_the_user_rated_highly(): void
    {
        $user = $this->makeUserWithReviews();

        $genre = Genre::factory()->create();

        // عمل قيّمه المستخدم عالياً في هذا التصنيف
        $liked = Title::factory()->create();
        $liked->genres()->attach($genre);
        Review::factory()->create(['user_id' => $user->id, 'title_id' => $liked->id, 'rating' => 5]);

        // توصية محتملة من نفس التصنيف (لم يراجعها المستخدم)
        $candidate = Title::factory()->create(['name' => 'فيلم موصى به']);
        $candidate->genres()->attach($genre);
        Review::factory()->create(['title_id' => $candidate->id, 'rating' => 5]);

        $this->actingAs($user)->get('/')
            ->assertOk()
            ->assertSee('موصى به لك')
            ->assertSee('فيلم موصى به');
    }

    public function test_guest_sees_general_picks(): void
    {
        $title = Title::factory()->create(['name' => 'اختيار عام']);
        Review::factory()->create(['title_id' => $title->id, 'rating' => 5]);

        $this->get('/')->assertOk()->assertSee('ترشيحات');
    }

    private function makeUserWithReviews(): User
    {
        return User::factory()->create();
    }
}
