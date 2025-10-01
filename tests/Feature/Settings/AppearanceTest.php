<?php

namespace Tests\Feature\Settings;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class AppearanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_settings_route_redirects_to_profile_settings(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/settings')
            ->assertRedirect(route('profile.edit'));
    }

    public function test_appearance_settings_requires_authentication(): void
    {
        $this->get(route('appearance.edit'))->assertRedirect(route('login'));

        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('appearance.edit'))
            ->assertStatus(200)
            ->assertInertia(fn (Assert $page): Assert => $page->component('settings/Appearance'));
    }
}
