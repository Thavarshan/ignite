<?php

use App\Models\Channel;
use App\Models\Reply;
use App\Models\Thread;
use App\Models\User;

test('authenticated user can flag a thread via web', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();

    $this->actingAs($user)
        ->post(route('threads.flag', $thread), ['reason' => 'spam'])
        ->assertRedirect();

    expect($thread->flags()->count())->toBe(1);
});

test('authenticated user can flag a reply via web', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $reply = Reply::factory()->for($thread)->for(User::factory())->create();

    $this->actingAs($user)
        ->post(route('replies.flag', [$thread, $reply]), ['reason' => 'inappropriate'])
        ->assertRedirect();

    expect($reply->flags()->count())->toBe(1);
});

test('user cannot flag the same thread twice via web', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $thread->flags()->create(['user_id' => $user->id, 'reason' => 'spam']);

    $this->actingAs($user)
        ->post(route('threads.flag', $thread), ['reason' => 'spam'])
        ->assertStatus(422);
});

test('user cannot flag their own thread via web', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for($user)->create();

    $this->actingAs($user)
        ->post(route('threads.flag', $thread), ['reason' => 'spam'])
        ->assertStatus(422);
});

test('user cannot flag their own reply via web', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $reply = Reply::factory()->for($thread)->for($user)->create();

    $this->actingAs($user)
        ->post(route('replies.flag', [$thread, $reply]), ['reason' => 'spam'])
        ->assertStatus(422);
});

test('flag requires a valid reason via web', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();

    $this->actingAs($user)
        ->post(route('threads.flag', $thread), ['reason' => 'invalid-reason'])
        ->assertSessionHasErrors('reason');
});

test('guest cannot flag a thread via web', function () {
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();

    $this->post(route('threads.flag', $thread), ['reason' => 'spam'])
        ->assertRedirect(route('login'));
});

test('reply flag validates thread ownership via web', function () {
    $user = User::factory()->create();
    $thread1 = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $thread2 = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $reply = Reply::factory()->for($thread1)->for(User::factory())->create();

    $this->actingAs($user)
        ->post(route('replies.flag', [$thread2, $reply]), ['reason' => 'spam'])
        ->assertStatus(422);
});
