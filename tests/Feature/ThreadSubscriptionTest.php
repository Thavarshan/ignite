<?php

use App\Models\Channel;
use App\Models\Thread;
use App\Models\User;

test('authenticated user can subscribe to a thread', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();

    $this->actingAs($user)
        ->postJson(route('api.threads.subscribe.store', $thread))
        ->assertSuccessful();

    expect($thread->isSubscribedTo($user))->toBeTrue();
});

test('subscribing twice does not create duplicate', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $thread->subscribe($user);

    $this->actingAs($user)
        ->postJson(route('api.threads.subscribe.store', $thread))
        ->assertSuccessful();

    expect($thread->subscriptions()->count())->toBe(1);
});

test('authenticated user can unsubscribe from a thread', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $thread->subscribe($user);

    $this->actingAs($user)
        ->deleteJson(route('api.threads.subscribe.destroy', $thread))
        ->assertSuccessful();

    expect($thread->isSubscribedTo($user))->toBeFalse();
});

test('unsubscribing when not subscribed does nothing', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();

    $this->actingAs($user)
        ->deleteJson(route('api.threads.subscribe.destroy', $thread))
        ->assertSuccessful();

    expect($thread->subscriptions()->count())->toBe(0);
});

test('replying to a thread auto-subscribes the user', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();

    $this->actingAs($user)
        ->postJson(route('api.threads.replies.store', $thread), ['body' => 'A reply.'])
        ->assertCreated();

    expect($thread->isSubscribedTo($user))->toBeTrue();
});

test('auto-subscribe on reply does not duplicate existing subscription', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $thread->subscribe($user);

    $this->actingAs($user)
        ->postJson(route('api.threads.replies.store', $thread), ['body' => 'Another reply.'])
        ->assertCreated();

    expect($thread->subscriptions()->count())->toBe(1);
});

test('guest cannot subscribe to a thread', function () {
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();

    $this->postJson(route('api.threads.subscribe.store', $thread))
        ->assertUnauthorized();
});
