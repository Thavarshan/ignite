<?php

use App\Models\Channel;
use App\Models\Reply;
use App\Models\Thread;
use App\Models\User;

test('authenticated user can view a user profile', function () {
    $user = User::factory()->create();
    $profile = User::factory()->create();

    $this->actingAs($user)
        ->getJson(route('api.users.show', $profile))
        ->assertSuccessful()
        ->assertJsonPath('data.id', $profile->id)
        ->assertJsonPath('data.name', $profile->name);
});

test('profile includes correct thread and reply counts', function () {
    $user = User::factory()->create();
    $profile = User::factory()->create();
    $channel = Channel::factory()->create();
    Thread::factory()->count(3)->for($channel)->for($profile)->create();
    $thread = Thread::factory()->for($channel)->for(User::factory())->create();
    Reply::factory()->count(2)->for($thread)->for($profile)->create();

    $this->actingAs($user)
        ->getJson(route('api.users.show', $profile))
        ->assertSuccessful()
        ->assertJsonPath('data.threads_count', 3)
        ->assertJsonPath('data.replies_count', 2);
});

test('authenticated user can view a user threads', function () {
    $user = User::factory()->create();
    $profile = User::factory()->create();
    $channel = Channel::factory()->create();
    Thread::factory()->count(3)->for($channel)->for($profile)->create();

    $this->actingAs($user)
        ->getJson(route('api.users.threads', $profile))
        ->assertSuccessful()
        ->assertJsonCount(3, 'data');
});

test('authenticated user can view a user replies', function () {
    $user = User::factory()->create();
    $profile = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    Reply::factory()->count(2)->for($thread)->for($profile)->create();

    $this->actingAs($user)
        ->getJson(route('api.users.replies', $profile))
        ->assertSuccessful()
        ->assertJsonCount(2, 'data');
});

test('guest cannot view user profile', function () {
    $profile = User::factory()->create();

    $this->getJson(route('api.users.show', $profile))
        ->assertUnauthorized();
});

test('returns 404 for non-existent user', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->getJson(route('api.users.show', 99999))
        ->assertNotFound();
});
