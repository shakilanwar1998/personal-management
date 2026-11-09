<?php

namespace Tests\Feature\Admin;

use App\Models\Settings;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_settings_page_renders(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/admin/settings')
            ->assertOk()
            ->assertInertia(fn ($page) => $page->component('Settings/Index')->has('data.counts'));
    }

    public function test_profile_update(): void
    {
        $user = User::factory()->create();
        User::factory()->create(['email' => 'taken@example.com']);

        $this->actingAs($user)->put('/admin/settings/profile', ['name' => 'New Name', 'email' => 'taken@example.com'])
            ->assertSessionHasErrors('email');

        $this->actingAs($user)->put('/admin/settings/profile', ['name' => 'New Name', 'email' => 'new@example.com'])
            ->assertSessionHasNoErrors();

        $user->refresh();
        $this->assertSame('New Name', $user->name);
        $this->assertSame('new@example.com', $user->email);
    }

    public function test_password_change_requires_current_password(): void
    {
        $user = User::factory()->create(['password' => 'old-password']);

        $this->actingAs($user)->put('/admin/settings/password', [
            'current_password' => 'wrong', 'password' => 'brand-new-pass', 'password_confirmation' => 'brand-new-pass',
        ])->assertSessionHasErrors('current_password');
        $this->assertTrue(Hash::check('old-password', $user->fresh()->password));

        $this->actingAs($user)->put('/admin/settings/password', [
            'current_password' => 'old-password', 'password' => 'brand-new-pass', 'password_confirmation' => 'mismatch',
        ])->assertSessionHasErrors('password');

        $this->actingAs($user)->put('/admin/settings/password', [
            'current_password' => 'old-password', 'password' => 'brand-new-pass', 'password_confirmation' => 'brand-new-pass',
        ])->assertSessionHasNoErrors();
        $this->assertTrue(Hash::check('brand-new-pass', $user->fresh()->password));
    }

    public function test_currency_preference(): void
    {
        $this->actingAs(User::factory()->create())
            ->put('/admin/settings/preferences', ['currency_symbol' => 'Tk'])
            ->assertSessionHasNoErrors();
        $this->assertSame('Tk', Settings::get('currency_symbol'));

        $this->actingAs(User::factory()->create())
            ->put('/admin/settings/preferences', ['currency_symbol' => 'TOOLONG'])
            ->assertSessionHasErrors('currency_symbol');
    }
}
