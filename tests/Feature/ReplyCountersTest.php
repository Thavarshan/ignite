<?php

use App\Models\Channel;
use App\Models\Reply;
use App\Models\Thread;
use App\Models\User;

test('creating a reply increments thread replies_count', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();

    expect($thread->replies_count)->toBe(0);

    Reply::factory()->for($thread)->for($user)->create();

    expect($thread->fresh()->replies_count)->toBe(1);
});

test('creating a reply updates thread last_posted_at', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create(['last_posted_at' => null]);

    expect($thread->last_posted_at)->toBeNull();

    Reply::factory()->for($thread)->for($user)->create();

    expect($thread->fresh()->last_posted_at)->not->toBeNull();
});

test('deleting a reply decrements thread replies_count', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $reply = Reply::factory()->for($thread)->for($user)->create();

    expect($thread->fresh()->replies_count)->toBe(1);

    $reply->delete();

    expect($thread->fresh()->replies_count)->toBe(0);
});

test('creating multiple replies increments count correctly', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();

    Reply::factory()->count(5)->for($thread)->for($user)->create();

    expect($thread->fresh()->replies_count)->toBe(5);
});

test('nested reply also increments thread replies_count', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $parent = Reply::factory()->for($thread)->for($user)->create();

    expect($thread->fresh()->replies_count)->toBe(1);

    Reply::factory()->asChild($parent)->for($user)->create();

    expect($thread->fresh()->replies_count)->toBe(2);
});

test('viewing a thread via api increments the visit counter', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create(['visits' => 0]);

    expect($thread->visits)->toBe(0);

    $this->actingAs($user)
        ->getJson(route('api.threads.show', $thread))
        ->assertSuccessful();

    expect($thread->fresh()->visits)->toBe(1);
});
