<?php

use App\Models\Channel;
use App\Models\Reply;
use App\Models\Thread;
use App\Models\User;

test('authenticated user can flag a thread', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();

    $this->actingAs($user)
        ->postJson(route('api.threads.flag', $thread), ['reason' => 'spam'])
        ->assertSuccessful()
        ->assertJsonPath('data.reason', 'spam');

    expect($thread->flags()->count())->toBe(1);
});

test('authenticated user can flag a reply', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $reply = Reply::factory()->for($thread)->for(User::factory())->create();

    $this->actingAs($user)
        ->postJson(route('api.replies.flag', $reply), ['reason' => 'inappropriate'])
        ->assertSuccessful();

    expect($reply->flags()->count())->toBe(1);
});

test('user cannot flag the same thread twice', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $thread->flags()->create(['user_id' => $user->id, 'reason' => 'spam']);

    $this->actingAs($user)
        ->postJson(route('api.threads.flag', $thread), ['reason' => 'spam'])
        ->assertStatus(422);
});

test('user cannot flag their own thread', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for($user)->create();

    $this->actingAs($user)
        ->postJson(route('api.threads.flag', $thread), ['reason' => 'spam'])
        ->assertStatus(422);
});

test('user cannot flag their own reply', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $reply = Reply::factory()->for($thread)->for($user)->create();

    $this->actingAs($user)
        ->postJson(route('api.replies.flag', $reply), ['reason' => 'spam'])
        ->assertStatus(422);
});

test('flag requires a valid reason', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();

    $this->actingAs($user)
        ->postJson(route('api.threads.flag', $thread), ['reason' => 'invalid-reason'])
        ->assertUnprocessable();
});

test('admin can view the moderation queue', function () {
    $admin = User::factory()->admin()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $thread->flags()->create(['user_id' => User::factory()->create()->id, 'reason' => 'spam']);

    $this->actingAs($admin)
        ->getJson(route('api.admin.flags.index'))
        ->assertSuccessful()
        ->assertJsonCount(1, 'data');
});

test('non-admin cannot view the moderation queue', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->getJson(route('api.admin.flags.index'))
        ->assertForbidden();
});

test('admin can filter flags by pending status', function () {
    $admin = User::factory()->admin()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $thread->flags()->create(['user_id' => User::factory()->create()->id, 'reason' => 'spam']);
    $thread->flags()->create(['user_id' => User::factory()->create()->id, 'reason' => 'off-topic', 'resolved_at' => now(), 'resolved_by' => $admin->id]);

    $this->actingAs($admin)
        ->getJson(route('api.admin.flags.index', ['status' => 'pending']))
        ->assertSuccessful()
        ->assertJsonCount(1, 'data');
});

test('admin can resolve a flag', function () {
    $admin = User::factory()->admin()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $flag = $thread->flags()->create(['user_id' => User::factory()->create()->id, 'reason' => 'spam']);

    $this->actingAs($admin)
        ->postJson(route('api.admin.flags.resolve', $flag))
        ->assertSuccessful();

    expect($flag->fresh()->resolved_at)->not->toBeNull()
        ->and($flag->fresh()->resolved_by)->toBe($admin->id);
});

test('guest cannot flag a thread', function () {
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();

    $this->postJson(route('api.threads.flag', $thread), ['reason' => 'spam'])
        ->assertUnauthorized();
});
