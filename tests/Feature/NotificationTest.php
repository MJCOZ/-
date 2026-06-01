<?php

namespace Tests\Feature;

use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_review_owner_is_notified_when_someone_comments(): void
    {
        $owner = User::factory()->create();
        $commenter = User::factory()->create();
        $review = Review::factory()->create(['user_id' => $owner->id]);

        $this->actingAs($commenter)
            ->post("/reviews/{$review->id}/comments", ['body' => 'رد على مراجعتك']);

        $this->assertCount(1, $owner->fresh()->notifications);
        $this->assertEquals('رد على مراجعتك', \Illuminate\Support\Str::limit($owner->notifications->first()->data['excerpt'], 60));
    }

    public function test_no_notification_when_commenting_on_own_review(): void
    {
        $owner = User::factory()->create();
        $review = Review::factory()->create(['user_id' => $owner->id]);

        $this->actingAs($owner)
            ->post("/reviews/{$review->id}/comments", ['body' => 'تعليق على نفسي']);

        $this->assertCount(0, $owner->fresh()->notifications);
    }

    public function test_notifications_page_marks_as_read(): void
    {
        $owner = User::factory()->create();
        $commenter = User::factory()->create();
        $review = Review::factory()->create(['user_id' => $owner->id]);

        $this->actingAs($commenter)->post("/reviews/{$review->id}/comments", ['body' => 'مرحباً']);
        $this->assertCount(1, $owner->fresh()->unreadNotifications);

        $this->actingAs($owner)->get('/notifications')->assertOk();
        $this->assertCount(0, $owner->fresh()->unreadNotifications);
    }
}
