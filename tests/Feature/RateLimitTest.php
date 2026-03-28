<?php

use App\Models\Channel;
use App\Models\Thread;
use App\Models\User;

test('api requests are rate limited to 60 per minute', function () {
    $user = User::factory()->create();
    $channel = Channel::factory()->create();
    Thread::factory()->for($channel)->for($user)->create();

    $this->actingAs($user);

    for ($i = 0; $i < 60; $i++) {
        $this->getJson(route('api.threads.index'))->assertSuccessful();
    }

    $this->getJson(route('api.threads.index'))->assertTooManyRequests();
});

test('content creation is rate limited to 10 per minute', function () {
    $user = User::factory()->create();
    $channel = Channel::factory()->create();

    $this->actingAs($user);

    for ($i = 0; $i < 10; $i++) {
        $this->postJson(route('api.threads.store'), [
            'title' => "Thread {$i}",
            'body' => 'This is a valid thread body.',
            'channel_id' => $channel->id,
        ])->assertCreated();
    }

    $this->postJson(route('api.threads.store'), [
        'title' => 'One more thread',
        'body' => 'This should be blocked.',
        'channel_id' => $channel->id,
    ])->assertTooManyRequests();
});
