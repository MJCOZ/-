<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InteractionTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_comment_on_a_review(): void
    {
        $user = User::factory()->create();
        $review = Review::factory()->create();

        $this->actingAs($user)
            ->post("/reviews/{$review->id}/comments", ['body' => 'رد رائع'])
            ->assertRedirect();

        $this->assertDatabaseHas('comments', [
            'review_id' => $review->id,
            'user_id' => $user->id,
            'body' => 'رد رائع',
        ]);
    }

    public function test_user_can_delete_own_comment(): void
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)->delete("/comments/{$comment->id}")->assertRedirect();
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }

    public function test_user_can_vote_helpful_on_a_review(): void
    {
        $author = User::factory()->create();
        $voter = User::factory()->create();
        $review = Review::factory()->create(['user_id' => $author->id]);

        $this->actingAs($voter)->post("/reviews/{$review->id}/vote", ['helpful' => 1])->assertRedirect();

        $this->assertDatabaseHas('review_likes', [
            'review_id' => $review->id,
            'user_id' => $voter->id,
            'helpful' => true,
        ]);
    }

    public function test_repeated_same_vote_is_toggled_off(): void
    {
        $author = User::factory()->create();
        $voter = User::factory()->create();
        $review = Review::factory()->create(['user_id' => $author->id]);

        $this->actingAs($voter)->post("/reviews/{$review->id}/vote", ['helpful' => 1]);
        $this->actingAs($voter)->post("/reviews/{$review->id}/vote", ['helpful' => 1]);

        $this->assertDatabaseCount('review_likes', 0);
    }

    public function test_user_cannot_vote_on_own_review(): void
    {
        $user = User::factory()->create();
        $review = Review::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)->post("/reviews/{$review->id}/vote", ['helpful' => 1])->assertForbidden();
        $this->assertDatabaseCount('review_likes', 0);
    }
}
