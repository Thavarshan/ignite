<?php

use App\Models\Channel;
use App\Models\Thread;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('authenticated users can view the thread index and create pages', function () {
    $user = User::factory()->create();
    $channel = Channel::factory()->create();

    Thread::factory()->count(2)->for($channel)->for($user)->create();

    $this->actingAs($user)
        ->get(route('threads.index'))
        ->assertOk()
        ->assertInertia(
            fn (Assert $page) => $page
                ->component('threads/Index', false)
                ->has('threads.data', 2)
        );

    $this->actingAs($user)
        ->get(route('threads.create'))
        ->assertOk()
        ->assertInertia(
            fn (Assert $page) => $page
                ->component('threads/Create', false)
                ->where('thread.id', null)
                ->has('channels', 1)
        );
});

test('authenticated users can create, view, and edit their own thread', function () {
    $user = User::factory()->create();
    $channel = Channel::factory()->create();

    $createResponse = $this->actingAs($user)->post(route('threads.store'), [
        'channel_id' => $channel->id,
        'title' => 'How do I improve query performance?',
        'body' => 'Looking for practical indexing tips.',
        'pinned' => false,
        'locked' => false,
    ]);

    $thread = Thread::query()->first();

    expect($thread)->not->toBeNull();
    expect($thread->slug)->toBe('how-do-i-improve-query-performance');
    expect($thread->user_id)->toBe($user->id);

    $createResponse->assertRedirect(route('threads.show', $thread, absolute: false));

    $this->actingAs($user)
        ->get(route('threads.show', $thread))
        ->assertOk()
        ->assertInertia(
            fn (Assert $page) => $page
                ->component('threads/Show', false)
                ->where('thread.id', $thread->id)
                ->where('thread.user_id', $user->id)
        );

    $this->actingAs($user)
        ->get(route('threads.edit', $thread))
        ->assertOk()
        ->assertInertia(
            fn (Assert $page) => $page
                ->component('threads/Edit', false)
                ->where('thread.id', $thread->id)
        );
});

test('thread owners can update and delete their own thread', function () {
    $user = User::factory()->create();
    $channel = Channel::factory()->create();
    $thread = Thread::factory()->for($channel)->for($user)->create([
        'title' => 'Old Thread Title',
        'body' => 'Old thread body',
    ]);

    $updateResponse = $this->actingAs($user)->put(route('threads.update', $thread), [
        'channel_id' => $channel->id,
        'title' => 'New Thread Title',
        'body' => 'Updated thread body',
    ]);

    $updateResponse->assertRedirect(route('threads.show', $thread->fresh(), absolute: false));

    expect($thread->fresh()->title)->toBe('New Thread Title');
    expect($thread->fresh()->slug)->toBe('new-thread-title');

    $deleteResponse = $this->actingAs($user)->delete(route('threads.destroy', $thread->fresh()));

    $deleteResponse->assertRedirect(route('threads.index', absolute: false));
    expect($thread->fresh()->trashed())->toBeTrue();
});

test('non owners can not update or delete a thread', function () {
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();
    $channel = Channel::factory()->create();
    $thread = Thread::factory()->for($channel)->for($owner)->create();

    $this->actingAs($otherUser)
        ->put(route('threads.update', $thread), [
            'channel_id' => $channel->id,
            'title' => 'Should Not Update',
            'body' => 'No permission',
            'pinned' => false,
            'locked' => false,
        ])
        ->assertForbidden();

    $this->actingAs($otherUser)
        ->delete(route('threads.destroy', $thread))
        ->assertForbidden();
});

test('authenticated users can filter the thread index by channel', function () {
    $user = User::factory()->create();
    $laravelChannel = Channel::factory()->create(['name' => 'Laravel']);
    $vueChannel = Channel::factory()->create(['name' => 'Vue']);

    Thread::factory()->for($laravelChannel)->for($user)->create([
        'title' => 'Laravel performance tips',
    ]);

    Thread::factory()->for($vueChannel)->for($user)->create([
        'title' => 'Vue router question',
    ]);

    $this->actingAs($user)
        ->get(route('threads.index', ['channel' => $laravelChannel->slug]))
        ->assertOk()
        ->assertInertia(
            fn (Assert $page) => $page
                ->component('threads/Index', false)
                ->has('threads.data', 1)
                ->where('threads.data.0.title', 'Laravel performance tips')
        );
});

test('admins can pin and lock a thread via the web', function () {
    $admin = User::factory()->admin()->create();
    $channel = Channel::factory()->create();
    $thread = Thread::factory()->for($channel)->for($admin)->create(['pinned' => false, 'locked' => false]);

    $this->actingAs($admin)
        ->put(route('threads.update', $thread), [
            'channel_id' => $channel->id,
            'title' => $thread->title,
            'body' => $thread->body,
            'pinned' => true,
            'locked' => true,
        ])
        ->assertRedirect();

    expect($thread->fresh()->pinned)->toBeTrue();
    expect($thread->fresh()->locked)->toBeTrue();
});

test('regular users cannot pin or lock a thread via the web', function () {
    $user = User::factory()->create();
    $channel = Channel::factory()->create();
    $thread = Thread::factory()->for($channel)->for($user)->create(['pinned' => false, 'locked' => false]);

    $this->actingAs($user)
        ->put(route('threads.update', $thread), [
            'channel_id' => $channel->id,
            'title' => $thread->title,
            'body' => $thread->body,
            'pinned' => true,
            'locked' => true,
        ])
        ->assertRedirect();

    expect($thread->fresh()->pinned)->toBeFalse();
    expect($thread->fresh()->locked)->toBeFalse();
});
