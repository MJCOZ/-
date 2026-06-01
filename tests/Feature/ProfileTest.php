<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_profile_edit(): void
    {
        $this->get('/profile/edit')->assertRedirect('/login');
    }

    public function test_user_can_update_name_and_email(): void
    {
        $user = User::factory()->create(['email' => 'old@example.com']);

        $this->actingAs($user)->put('/profile', [
            'name' => 'اسمي الجديد',
            'email' => 'new@example.com',
        ])->assertRedirect();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'اسمي الجديد',
            'email' => 'new@example.com',
        ]);
    }

    public function test_email_must_be_unique_when_updating(): void
    {
        User::factory()->create(['email' => 'taken@example.com']);
        $user = User::factory()->create(['email' => 'mine@example.com']);

        $this->actingAs($user)
            ->from('/profile/edit')
            ->put('/profile', ['name' => $user->name, 'email' => 'taken@example.com'])
            ->assertSessionHasErrors('email');
    }

    public function test_user_can_change_password_with_correct_current_password(): void
    {
        $user = User::factory()->create(['password' => Hash::make('oldpass123')]);

        $this->actingAs($user)->put('/profile/password', [
            'current_password' => 'oldpass123',
            'password' => 'newpass456',
            'password_confirmation' => 'newpass456',
        ])->assertRedirect();

        $this->assertTrue(Hash::check('newpass456', $user->fresh()->password));
    }

    public function test_password_change_fails_with_wrong_current_password(): void
    {
        $user = User::factory()->create(['password' => Hash::make('oldpass123')]);

        $this->actingAs($user)
            ->from('/profile/edit')
            ->put('/profile/password', [
                'current_password' => 'wrongpass',
                'password' => 'newpass456',
                'password_confirmation' => 'newpass456',
            ])
            ->assertSessionHasErrors('current_password');

        $this->assertTrue(Hash::check('oldpass123', $user->fresh()->password));
    }
}
