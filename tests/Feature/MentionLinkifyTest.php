<?php

use App\Models\Channel;
use App\Models\Reply;
use App\Models\Thread;
use App\Models\User;

test('thread API response contains linkified mention in body', function () {
    $user = User::factory()->create();
    $mentioned = User::factory()->create(['name' => 'Taylor']);
    $thread = Thread::factory()
        ->for(Channel::factory())
        ->for($user)
        ->create(['body' => 'Thanks @Taylor for the help!']);

    $this->actingAs($user)
        ->getJson(route('api.threads.show', $thread))
        ->assertSuccessful()
        ->assertJsonPath('data.body', "Thanks [@Taylor](/users/{$mentioned->id}) for the help!");
});

test('reply API response contains linkified mention in body', function () {
    $user = User::factory()->create();
    $mentioned = User::factory()->create(['name' => 'Nuno']);
    $thread = Thread::factory()->for(Channel::factory())->for($user)->create();
    Reply::factory()->for($thread)->for($user)->create(['body' => 'Agree with @Nuno']);

    $this->actingAs($user)
        ->getJson(route('api.threads.replies.index', $thread))
        ->assertSuccessful()
        ->assertJsonPath('data.0.body', "Agree with [@Nuno](/users/{$mentioned->id})");
});

test('thread body with no mentions is unchanged in API response', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()
        ->for(Channel::factory())
        ->for($user)
        ->create(['body' => 'No mentions here.']);

    $this->actingAs($user)
        ->getJson(route('api.threads.show', $thread))
        ->assertSuccessful()
        ->assertJsonPath('data.body', 'No mentions here.');
});
