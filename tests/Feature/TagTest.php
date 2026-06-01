<?php

namespace Tests\Feature;

use App\Models\Tag;
use App\Models\Title;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TagTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_tag(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->post('/admin/tags', ['name' => 'وسم جديد'])
            ->assertRedirect('/admin/tags');
        $this->assertDatabaseHas('tags', ['name' => 'وسم جديد']);
    }

    public function test_admin_can_attach_tags_to_title(): void
    {
        $admin = User::factory()->admin()->create();
        $title = Title::factory()->create();
        $tags = Tag::factory()->count(2)->create();

        $this->actingAs($admin)->put("/admin/titles/{$title->id}", [
            'name' => $title->name,
            'type' => $title->type,
            'tags' => $tags->pluck('id')->all(),
        ])->assertRedirect('/admin/titles');

        $this->assertEquals(2, $title->fresh()->tags()->count());
    }

    public function test_tag_page_lists_its_titles(): void
    {
        $tag = Tag::factory()->create(['name' => 'وسمي']);
        $title = Title::factory()->create(['name' => 'عمل موسوم']);
        $title->tags()->attach($tag);

        $this->get("/tags/{$tag->id}")
            ->assertOk()
            ->assertSee('عمل موسوم');
    }
}
