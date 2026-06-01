<?php

namespace Tests\Feature;

use App\Models\Review;
use App\Models\Title;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_post_review(): void
    {
        $title = Title::factory()->create();

        $this->post("/titles/{$title->id}/reviews", ['rating' => 5, 'body' => 'رائع جداً'])
            ->assertRedirect('/login');
        $this->assertDatabaseCount('reviews', 0);
    }

    public function test_authenticated_user_can_create_review(): void
    {
        $user = User::factory()->create();
        $title = Title::factory()->create();

        $this->actingAs($user)
            ->post("/titles/{$title->id}/reviews", ['rating' => 4, 'body' => 'عمل ممتاز'])
            ->assertRedirect("/titles/{$title->id}");

        $this->assertDatabaseHas('reviews', [
            'user_id' => $user->id,
            'title_id' => $title->id,
            'rating' => 4,
            'body' => 'عمل ممتاز',
        ]);
    }

    public function test_review_requires_valid_rating(): void
    {
        $user = User::factory()->create();
        $title = Title::factory()->create();

        $this->actingAs($user)
            ->post("/titles/{$title->id}/reviews", ['rating' => 9, 'body' => 'نص'])
            ->assertSessionHasErrors('rating');
    }

    public function test_second_review_updates_the_existing_one(): void
    {
        $user = User::factory()->create();
        $title = Title::factory()->create();

        $this->actingAs($user)->post("/titles/{$title->id}/reviews", ['rating' => 3, 'body' => 'أولي']);
        $this->actingAs($user)->post("/titles/{$title->id}/reviews", ['rating' => 5, 'body' => 'محدّثة']);

        $this->assertDatabaseCount('reviews', 1);
        $this->assertDatabaseHas('reviews', ['rating' => 5, 'body' => 'محدّثة']);
    }

    public function test_user_can_delete_own_review(): void
    {
        $user = User::factory()->create();
        $review = Review::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)->delete("/reviews/{$review->id}")->assertRedirect();
        $this->assertDatabaseMissing('reviews', ['id' => $review->id]);
    }

    public function test_user_cannot_delete_others_review(): void
    {
        $review = Review::factory()->create();
        $other = User::factory()->create();

        $this->actingAs($other)->delete("/reviews/{$review->id}")->assertForbidden();
        $this->assertDatabaseHas('reviews', ['id' => $review->id]);
    }
}
