<?php

use App\Models\Channel;
use App\Models\Thread;
use App\Models\User;

test('authenticated user can subscribe to a thread via web', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();

    $this->actingAs($user)
        ->post(route('threads.subscribe.store', $thread))
        ->assertRedirect();

    expect($thread->isSubscribedTo($user))->toBeTrue();
});

test('authenticated user can unsubscribe from a thread via web', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $thread->subscribe($user);

    $this->actingAs($user)
        ->delete(route('threads.subscribe.destroy', $thread))
        ->assertRedirect();

    expect($thread->isSubscribedTo($user))->toBeFalse();
});

test('guest cannot subscribe to a thread via web', function () {
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();

    $this->post(route('threads.subscribe.store', $thread))
        ->assertRedirect(route('login'));
});

test('subscribing via web twice does not create duplicate', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $thread->subscribe($user);

    $this->actingAs($user)
        ->post(route('threads.subscribe.store', $thread))
        ->assertRedirect();

    expect($thread->subscriptions()->count())->toBe(1);
});
