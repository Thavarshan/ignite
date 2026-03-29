<?php

use App\Models\Channel;
use App\Models\Reply;
use App\Models\Thread;
use App\Models\User;

// ── Thread Likes (Web) ─────────────────────────────

test('authenticated user can like a thread via web', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();

    $this->actingAs($user)
        ->post(route('threads.like.store', $thread))
        ->assertRedirect();

    expect($thread->likes()->count())->toBe(1);
});

test('user cannot like the same thread twice via web', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $thread->like($user);

    $this->actingAs($user)
        ->post(route('threads.like.store', $thread))
        ->assertStatus(422);
});

test('authenticated user can unlike a thread via web', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $thread->like($user);

    $this->actingAs($user)
        ->delete(route('threads.like.destroy', $thread))
        ->assertRedirect();

    expect($thread->likes()->count())->toBe(0);
});

test('guest cannot like a thread via web', function () {
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();

    $this->post(route('threads.like.store', $thread))
        ->assertRedirect(route('login'));
});

// ── Reply Likes (Web) ──────────────────────────────

test('authenticated user can like a reply via web', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $reply = Reply::factory()->for($thread)->for(User::factory())->create();

    $this->actingAs($user)
        ->post(route('replies.like.store', [$thread, $reply]))
        ->assertRedirect();

    expect($reply->likes()->count())->toBe(1);
});

test('user cannot like the same reply twice via web', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $reply = Reply::factory()->for($thread)->for(User::factory())->create();
    $reply->like($user);

    $this->actingAs($user)
        ->post(route('replies.like.store', [$thread, $reply]))
        ->assertStatus(422);
});

test('authenticated user can unlike a reply via web', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $reply = Reply::factory()->for($thread)->for(User::factory())->create();
    $reply->like($user);

    $this->actingAs($user)
        ->delete(route('replies.like.destroy', [$thread, $reply]))
        ->assertRedirect();

    expect($reply->likes()->count())->toBe(0);
});

test('reply like validates thread ownership', function () {
    $user = User::factory()->create();
    $thread1 = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $thread2 = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $reply = Reply::factory()->for($thread1)->for(User::factory())->create();

    $this->actingAs($user)
        ->post(route('replies.like.store', [$thread2, $reply]))
        ->assertStatus(422);
});
