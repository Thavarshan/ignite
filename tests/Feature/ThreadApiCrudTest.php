<?php

use App\Models\Channel;
use App\Models\Thread;
use App\Models\User;

test('authenticated api users can list and show threads', function () {
    $user = User::factory()->create();
    $channel = Channel::factory()->create();
    $thread = Thread::factory()->for($channel)->for($user)->create();

    $this->actingAs($user)
        ->getJson(route('api.threads.index'))
        ->assertSuccessful()
        ->assertJsonCount(1, 'data');

    $this->actingAs($user)
        ->getJson(route('api.threads.show', $thread))
        ->assertSuccessful()
        ->assertJsonPath('data.id', $thread->id)
        ->assertJsonPath('data.channel_id', $channel->id)
        ->assertJsonPath('data.user_id', $user->id);
});

test('authenticated api users can create threads', function () {
    $user = User::factory()->create();
    $channel = Channel::factory()->create();

    $this->actingAs($user)
        ->postJson(route('api.threads.store'), [
            'channel_id' => $channel->id,
            'title' => 'How should I structure my policies?',
            'body' => 'Need advice on policy boundaries.',
            'pinned' => false,
            'locked' => false,
        ])
        ->assertCreated()
        ->assertJsonPath('data.slug', 'how-should-i-structure-my-policies')
        ->assertJsonPath('data.user_id', $user->id);
});

test('thread owners can update and delete threads via api', function () {
    $user = User::factory()->create();
    $channel = Channel::factory()->create();
    $thread = Thread::factory()->for($channel)->for($user)->create([
        'title' => 'Old API Thread Title',
    ]);

    $this->actingAs($user)
        ->putJson(route('api.threads.update', $thread), [
            'channel_id' => $channel->id,
            'title' => 'New API Thread Title',
            'body' => 'Updated body',
        ])
        ->assertSuccessful()
        ->assertJsonPath('data.slug', 'new-api-thread-title');

    $this->actingAs($user)
        ->deleteJson(route('api.threads.destroy', $thread->fresh()))
        ->assertNoContent();
});

test('non owners can not update or delete threads via api', function () {
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();
    $channel = Channel::factory()->create();
    $thread = Thread::factory()->for($channel)->for($owner)->create();

    $this->actingAs($otherUser)
        ->putJson(route('api.threads.update', $thread), [
            'channel_id' => $channel->id,
            'title' => 'No Access',
            'body' => 'No Access',
            'pinned' => false,
            'locked' => false,
        ])
        ->assertForbidden();

    $this->actingAs($otherUser)
        ->deleteJson(route('api.threads.destroy', $thread))
        ->assertForbidden();
});

test('authenticated api users can filter threads by channel and search term', function () {
    $user = User::factory()->create();
    $laravelChannel = Channel::factory()->create(['name' => 'Laravel']);
    $vueChannel = Channel::factory()->create(['name' => 'Vue']);

    Thread::factory()->for($laravelChannel)->for($user)->create([
        'title' => 'Laravel queues are slow',
        'body' => 'Need help optimizing queue throughput.',
    ]);

    Thread::factory()->for($vueChannel)->for($user)->create([
        'title' => 'Vue hydration issue',
        'body' => 'Hydration mismatch on navigation.',
    ]);

    $this->actingAs($user)
        ->getJson(route('api.threads.index', [
            'channel' => $laravelChannel->slug,
            'q' => 'queues',
        ]))
        ->assertSuccessful()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.title', 'Laravel queues are slow');
});

test('authenticated api users can sort threads by popularity', function () {
    $user = User::factory()->create();
    $channel = Channel::factory()->create();

    Thread::factory()->for($channel)->for($user)->create([
        'title' => 'Less Popular Thread',
        'replies_count' => 1,
        'visits' => 10,
    ]);

    Thread::factory()->for($channel)->for($user)->create([
        'title' => 'Most Popular Thread',
        'replies_count' => 12,
        'visits' => 150,
    ]);

    $this->actingAs($user)
        ->getJson(route('api.threads.index', ['sort_by' => 'popular']))
        ->assertSuccessful()
        ->assertJsonPath('data.0.title', 'Most Popular Thread');
});

test('admins can set locked and pinned when creating or updating a thread', function () {
    $admin = User::factory()->admin()->create();
    $channel = Channel::factory()->create();

    $this->actingAs($admin)
        ->postJson(route('api.threads.store'), [
            'channel_id' => $channel->id,
            'title' => 'Announcement Thread',
            'body' => 'Important info.',
            'pinned' => true,
            'locked' => true,
        ])
        ->assertCreated()
        ->assertJsonPath('data.pinned', true)
        ->assertJsonPath('data.locked', true);

    $thread = Thread::query()->latest()->first();

    $this->actingAs($admin)
        ->putJson(route('api.threads.update', $thread), [
            'channel_id' => $channel->id,
            'title' => 'Announcement Thread',
            'body' => 'Important info.',
            'pinned' => false,
            'locked' => false,
        ])
        ->assertSuccessful()
        ->assertJsonPath('data.pinned', false)
        ->assertJsonPath('data.locked', false);
});

test('regular users cannot set locked or pinned on a thread', function () {
    $user = User::factory()->create();
    $channel = Channel::factory()->create();
    $thread = Thread::factory()->for($channel)->for($user)->create(['pinned' => false, 'locked' => false]);

    $this->actingAs($user)
        ->postJson(route('api.threads.store'), [
            'channel_id' => $channel->id,
            'title' => 'My Thread',
            'body' => 'Body text.',
            'pinned' => true,
            'locked' => true,
        ])
        ->assertCreated()
        ->assertJsonPath('data.pinned', false)
        ->assertJsonPath('data.locked', false);

    $this->actingAs($user)
        ->putJson(route('api.threads.update', $thread), [
            'channel_id' => $channel->id,
            'title' => 'My Thread',
            'body' => 'Body text.',
            'pinned' => true,
            'locked' => true,
        ])
        ->assertSuccessful()
        ->assertJsonPath('data.pinned', false)
        ->assertJsonPath('data.locked', false);
});

test('threads can be filtered by pinned=1 and pinned=0', function () {
    $admin = User::factory()->admin()->create();
    $channel = Channel::factory()->create();

    Thread::factory()->for($channel)->for($admin)->create(['title' => 'Pinned Thread', 'pinned' => true]);
    Thread::factory()->for($channel)->for($admin)->create(['title' => 'Normal Thread', 'pinned' => false]);

    $this->actingAs($admin)
        ->getJson(route('api.threads.index', ['pinned' => '1']))
        ->assertSuccessful()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.title', 'Pinned Thread');

    $this->actingAs($admin)
        ->getJson(route('api.threads.index', ['pinned' => '0']))
        ->assertSuccessful()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.title', 'Normal Thread');
});

test('unauthenticated requests to api thread routes return 401', function () {
    $user = User::factory()->create();
    $channel = Channel::factory()->create();
    $thread = Thread::factory()->for($channel)->for($user)->create();

    $this->getJson(route('api.threads.index'))->assertUnauthorized();
    $this->postJson(route('api.threads.store'), [])->assertUnauthorized();
    $this->getJson(route('api.threads.show', $thread))->assertUnauthorized();
    $this->putJson(route('api.threads.update', $thread), [])->assertUnauthorized();
    $this->deleteJson(route('api.threads.destroy', $thread))->assertUnauthorized();
});

test('invalid sort_by value returns 422', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->getJson(route('api.threads.index', ['sort_by' => 'invalid']))
        ->assertUnprocessable();
});

test('invalid pinned or locked filter value returns 422', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->getJson(route('api.threads.index', ['pinned' => 'yes']))
        ->assertUnprocessable();

    $this->actingAs($user)
        ->getJson(route('api.threads.index', ['locked' => 'true']))
        ->assertUnprocessable();
});
