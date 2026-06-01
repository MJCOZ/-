<?php

namespace Tests\Feature;

use App\Models\Title;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WatchlistTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_use_watchlist(): void
    {
        $title = Title::factory()->create();

        $this->post("/watchlist/{$title->id}/toggle")->assertRedirect('/login');
        $this->get('/watchlist')->assertRedirect('/login');
    }

    public function test_user_can_add_and_remove_title_from_watchlist(): void
    {
        $user = User::factory()->create();
        $title = Title::factory()->create();

        // add
        $this->actingAs($user)->post("/watchlist/{$title->id}/toggle")->assertRedirect();
        $this->assertDatabaseHas('watchlists', ['user_id' => $user->id, 'title_id' => $title->id]);

        // remove (toggle again)
        $this->actingAs($user)->post("/watchlist/{$title->id}/toggle")->assertRedirect();
        $this->assertDatabaseMissing('watchlists', ['user_id' => $user->id, 'title_id' => $title->id]);
    }

    public function test_watchlist_page_shows_only_users_titles(): void
    {
        $user = User::factory()->create();
        $mine = Title::factory()->create(['name' => 'عملي المحفوظ']);
        $other = Title::factory()->create(['name' => 'عمل غير محفوظ']);

        $user->watchlist()->attach($mine);

        $this->actingAs($user)->get('/watchlist')
            ->assertOk()
            ->assertSee('عملي المحفوظ')
            ->assertDontSee('عمل غير محفوظ');
    }
}
