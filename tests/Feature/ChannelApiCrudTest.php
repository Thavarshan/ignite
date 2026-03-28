<?php

use App\Models\Channel;
use App\Models\User;

test('authenticated api users can list channels', function () {
    $this->actingAs(User::factory()->create());

    Channel::factory()->count(2)->create();

    $response = $this->getJson(route('api.channels.index'));

    $response
        ->assertSuccessful()
        ->assertJsonCount(2, 'data');
});

test('authenticated api users can create channels', function () {
    $this->actingAs(User::factory()->admin()->create());

    $response = $this->postJson(route('api.channels.store'), [
        'name' => 'General Discussion',
        'description' => 'A place for everything.',
    ]);

    $response
        ->assertCreated()
        ->assertJsonPath('data.slug', 'general-discussion');

    expect(Channel::query()->first()?->name)->toBe('General Discussion');
});

test('authenticated api users can show a channel', function () {
    $this->actingAs(User::factory()->create());

    $channel = Channel::factory()->create();

    $this->getJson(route('api.channels.show', $channel))
        ->assertSuccessful()
        ->assertJsonPath('data.id', $channel->id)
        ->assertJsonPath('data.slug', $channel->slug);
});

test('authenticated api users can update a channel', function () {
    $this->actingAs(User::factory()->admin()->create());

    $channel = Channel::factory()->create([
        'name' => 'General Discussion',
    ]);

    $this->putJson(route('api.channels.update', $channel), [
        'name' => 'Announcements',
        'description' => 'Official updates only.',
    ])
        ->assertSuccessful()
        ->assertJsonPath('data.name', 'Announcements')
        ->assertJsonPath('data.slug', 'announcements');
});

test('authenticated api users can delete a channel', function () {
    $this->actingAs(User::factory()->admin()->create());

    $channel = Channel::factory()->create();

    $this->deleteJson(route('api.channels.destroy', $channel))
        ->assertNoContent();

    expect($channel->fresh())->toBeNull();
});

test('authenticated api users can filter channels by query string', function () {
    $this->actingAs(User::factory()->create());

    Channel::factory()->create([
        'name' => 'Laravel Help',
        'description' => 'Framework support and discussion.',
    ]);

    Channel::factory()->create([
        'name' => 'Vue Frontend',
        'description' => 'Frontend talk.',
    ]);

    $this->getJson(route('api.channels.index', ['q' => 'Laravel']))
        ->assertSuccessful()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.name', 'Laravel Help');
});

test('non admins cannot create, update, or delete channels via api', function () {
    $user = User::factory()->create();
    $channel = Channel::factory()->create();

    $this->actingAs($user)
        ->postJson(route('api.channels.store'), ['name' => 'New Channel', 'description' => null])
        ->assertForbidden();

    $this->actingAs($user)
        ->putJson(route('api.channels.update', $channel), ['name' => 'Renamed', 'description' => null])
        ->assertForbidden();

    $this->actingAs($user)
        ->deleteJson(route('api.channels.destroy', $channel))
        ->assertForbidden();
});

test('unauthenticated requests to api channel routes return 401', function () {
    $channel = Channel::factory()->create();

    $this->getJson(route('api.channels.index'))->assertUnauthorized();
    $this->postJson(route('api.channels.store'), [])->assertUnauthorized();
    $this->getJson(route('api.channels.show', $channel))->assertUnauthorized();
    $this->putJson(route('api.channels.update', $channel), [])->assertUnauthorized();
    $this->deleteJson(route('api.channels.destroy', $channel))->assertUnauthorized();
});
