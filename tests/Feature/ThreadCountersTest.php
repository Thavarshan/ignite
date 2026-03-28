<?php

use App\Models\Channel;
use App\Models\Thread;
use App\Models\User;

test('viewing a thread via api increments the visit counter', function () {
    $user = User::factory()->create();
    $channel = Channel::factory()->create();
    $thread = Thread::factory()->for($channel)->for($user)->create(['visits' => 0]);

    $this->actingAs($user)
        ->getJson(route('api.threads.show', $thread))
        ->assertSuccessful()
        ->assertJsonPath('data.visits', 1);

    expect($thread->fresh()->visits)->toBe(1);

    $this->actingAs($user)->getJson(route('api.threads.show', $thread));

    expect($thread->fresh()->visits)->toBe(2);
});

test('incrementRepliesCount increments counter and updates last_posted_at', function () {
    $thread = Thread::factory()
        ->for(Channel::factory())
        ->for(User::factory())
        ->create(['replies_count' => 0, 'last_posted_at' => now()->subDay()]);

    $before = $thread->last_posted_at;

    $thread->incrementRepliesCount();

    expect($thread->fresh()->replies_count)->toBe(1);
    expect($thread->fresh()->last_posted_at)->toBeGreaterThan($before);
});

test('decrementRepliesCount decrements the counter', function () {
    $thread = Thread::factory()
        ->for(Channel::factory())
        ->for(User::factory())
        ->create(['replies_count' => 3]);

    $thread->decrementRepliesCount();

    expect($thread->fresh()->replies_count)->toBe(2);
});
