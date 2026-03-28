<?php

use App\Models\Channel;
use App\Models\Reply;
use App\Models\Thread;
use App\Models\User;

// Thread likes

test('authenticated user can like a thread', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();

    $this->actingAs($user)
        ->postJson(route('api.threads.like.store', $thread))
        ->assertSuccessful();

    expect($thread->likes()->count())->toBe(1);
});

test('liking a thread increments its likes_count', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();

    $this->actingAs($user)
        ->postJson(route('api.threads.like.store', $thread))
        ->assertSuccessful()
        ->assertJsonPath('data.likes_count', 1);
});

test('user cannot like the same thread twice', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $thread->like($user);

    $this->actingAs($user)
        ->postJson(route('api.threads.like.store', $thread))
        ->assertStatus(422);
});

test('authenticated user can unlike a thread', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $thread->like($user);

    $this->actingAs($user)
        ->deleteJson(route('api.threads.like.destroy', $thread))
        ->assertSuccessful();

    expect($thread->likes()->count())->toBe(0);
});

test('unliking a thread decrements its likes_count', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $thread->like($user);

    $this->actingAs($user)
        ->deleteJson(route('api.threads.like.destroy', $thread))
        ->assertSuccessful()
        ->assertJsonPath('data.likes_count', 0);
});

test('unliking a thread you have not liked does nothing', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();

    $this->actingAs($user)
        ->deleteJson(route('api.threads.like.destroy', $thread))
        ->assertSuccessful()
        ->assertJsonPath('data.likes_count', 0);

    expect($thread->likes()->count())->toBe(0);
});

test('guest cannot like a thread', function () {
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();

    $this->postJson(route('api.threads.like.store', $thread))
        ->assertUnauthorized();
});

// Reply likes

test('authenticated user can like a reply', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $reply = Reply::factory()->for($thread)->for(User::factory())->create();

    $this->actingAs($user)
        ->postJson(route('api.replies.like.store', $reply))
        ->assertSuccessful();

    expect($reply->likes()->count())->toBe(1);
});

test('liking a reply increments its likes_count', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $reply = Reply::factory()->for($thread)->for(User::factory())->create();

    $this->actingAs($user)
        ->postJson(route('api.replies.like.store', $reply))
        ->assertSuccessful()
        ->assertJsonPath('data.likes_count', 1);
});

test('user cannot like the same reply twice', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $reply = Reply::factory()->for($thread)->for(User::factory())->create();
    $reply->like($user);

    $this->actingAs($user)
        ->postJson(route('api.replies.like.store', $reply))
        ->assertStatus(422);
});

test('authenticated user can unlike a reply', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $reply = Reply::factory()->for($thread)->for(User::factory())->create();
    $reply->like($user);

    $this->actingAs($user)
        ->deleteJson(route('api.replies.like.destroy', $reply))
        ->assertSuccessful();

    expect($reply->likes()->count())->toBe(0);
});

test('unliking a reply decrements its likes_count', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $reply = Reply::factory()->for($thread)->for(User::factory())->create();
    $reply->like($user);

    $this->actingAs($user)
        ->deleteJson(route('api.replies.like.destroy', $reply))
        ->assertSuccessful()
        ->assertJsonPath('data.likes_count', 0);
});

test('guest cannot like a reply', function () {
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $reply = Reply::factory()->for($thread)->for(User::factory())->create();

    $this->postJson(route('api.replies.like.store', $reply))
        ->assertUnauthorized();
});
