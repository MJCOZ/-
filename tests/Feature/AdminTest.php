<?php

namespace Tests\Feature;

use App\Models\Genre;
use App\Models\Title;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_admin(): void
    {
        $this->get('/admin')->assertRedirect('/login');
    }

    public function test_normal_user_is_forbidden_from_admin(): void
    {
        $this->actingAs(User::factory()->create())->get('/admin')->assertForbidden();
    }

    public function test_editor_can_access_dashboard(): void
    {
        $this->actingAs(User::factory()->editor()->create())->get('/admin')->assertOk();
    }

    public function test_editor_cannot_access_users_management(): void
    {
        $this->actingAs(User::factory()->editor()->create())->get('/admin/users')->assertForbidden();
    }

    public function test_admin_can_access_users_management(): void
    {
        $this->actingAs(User::factory()->admin()->create())->get('/admin/users')->assertOk();
    }

    public function test_admin_create_title_page_loads(): void
    {
        $this->actingAs(User::factory()->admin()->create())
            ->get('/admin/titles/create')
            ->assertOk();
    }

    public function test_admin_edit_title_page_loads(): void
    {
        $title = Title::factory()->create();
        $this->actingAs(User::factory()->admin()->create())
            ->get("/admin/titles/{$title->id}/edit")
            ->assertOk();
    }

    public function test_admin_can_create_title(): void
    {
        $admin = User::factory()->admin()->create();
        $genre = Genre::factory()->create();

        $this->actingAs($admin)->post('/admin/titles', [
            'name' => 'عمل جديد',
            'type' => 'movie',
            'genres' => [$genre->id],
            'release_year' => 2020,
            'platform' => 'Netflix',
            'watch_url' => 'https://example.com/watch',
            'imdb_rating' => 8.5,
            'rt_rating' => 90,
            'personal_rating' => 9,
            'watched' => 1,
        ])->assertRedirect('/admin/titles');

        $this->assertDatabaseHas('titles', ['name' => 'عمل جديد', 'watched' => true, 'platform' => 'Netflix']);
        $this->assertDatabaseHas('genre_title', ['genre_id' => $genre->id]);
    }

    public function test_admin_can_upload_poster_image(): void
    {
        Storage::fake('public');
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->post('/admin/titles', [
            'name' => 'عمل ببوستر',
            'type' => 'movie',
            'poster_file' => UploadedFile::fake()->image('poster.jpg', 400, 600),
        ])->assertRedirect('/admin/titles');

        $title = Title::where('name', 'عمل ببوستر')->first();
        $this->assertNotNull($title->poster);
        $this->assertStringStartsWith('posters/', $title->poster);
        Storage::disk('public')->assertExists($title->poster);
    }

    public function test_admin_can_update_and_delete_title(): void
    {
        $admin = User::factory()->admin()->create();
        $title = Title::factory()->create(['name' => 'قديم']);

        $this->actingAs($admin)->put("/admin/titles/{$title->id}", [
            'name' => 'محدّث',
            'type' => $title->type,
        ])->assertRedirect('/admin/titles');
        $this->assertDatabaseHas('titles', ['id' => $title->id, 'name' => 'محدّث']);

        $this->actingAs($admin)->delete("/admin/titles/{$title->id}")->assertRedirect('/admin/titles');
        $this->assertDatabaseMissing('titles', ['id' => $title->id]);
    }

    public function test_admin_can_create_genre(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->post('/admin/genres', ['name' => 'تصنيف جديد'])
            ->assertRedirect('/admin/genres');
        $this->assertDatabaseHas('genres', ['name' => 'تصنيف جديد']);
    }

    public function test_admin_can_change_user_role(): void
    {
        $admin = User::factory()->admin()->create();
        $target = User::factory()->create(['role' => 'user']);

        $this->actingAs($admin)->put("/admin/users/{$target->id}", ['role' => 'editor'])
            ->assertRedirect();
        $this->assertDatabaseHas('users', ['id' => $target->id, 'role' => 'editor']);
    }

    public function test_admin_cannot_change_own_role(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->put("/admin/users/{$admin->id}", ['role' => 'user'])
            ->assertSessionHasErrors('role');
        $this->assertDatabaseHas('users', ['id' => $admin->id, 'role' => 'admin']);
    }
}
