<?php

use App\Models\Channel;
use App\Models\Thread;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

// ── User Profile Web ────────────────────────────────

test('guest can view a user profile page', function () {
    $profile = User::factory()->create();
    $channel = Channel::factory()->create();
    Thread::factory()->count(2)->for($channel)->for($profile)->create();

    $this->get(route('users.show', $profile))
        ->assertOk()
        ->assertInertia(
            fn (Assert $page) => $page
                ->component('users/Show', false)
                ->where('profileUser.id', $profile->id)
                ->where('profileUser.name', $profile->name)
                ->where('profileUser.threads_count', 2)
        );
});

test('authenticated user can view another user profile', function () {
    $user = User::factory()->create();
    $profile = User::factory()->create();

    $this->actingAs($user)
        ->get(route('users.show', $profile))
        ->assertOk()
        ->assertInertia(
            fn (Assert $page) => $page
                ->component('users/Show', false)
                ->where('profileUser.id', $profile->id)
        );
});
