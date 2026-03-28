<?php

use App\Models\Channel;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('authenticated users can view the channel index page', function () {
    $user = User::factory()->create();

    Channel::factory()->count(2)->create();

    $response = $this->actingAs($user)->get(route('channels.index'));

    $response
        ->assertOk()
        ->assertInertia(
            fn (Assert $page) => $page
                ->component('channels/Index', false)
                ->has('channels.data', 2)
        );
});

test('authenticated users can view the channel creation page', function () {
    $user = User::factory()->admin()->create();

    $response = $this->actingAs($user)->get(route('channels.create'));

    $response
        ->assertOk()
        ->assertInertia(
            fn (Assert $page) => $page
                ->component('channels/Create', false)
                ->where('channel.id', null)
                ->where('channel.slug', null)
        );
});

test('authenticated users can create channels', function () {
    $user = User::factory()->admin()->create();

    $response = $this->actingAs($user)->post(route('channels.store'), [
        'name' => 'General Discussion',
        'description' => 'A place for everything.',
    ]);

    $channel = Channel::query()->first();

    expect($channel)->not->toBeNull();
    expect($channel->slug)->toBe('general-discussion');

    $response->assertRedirect(route('channels.show', $channel, absolute: false));
});

test('authenticated users can view and edit a channel page', function () {
    $user = User::factory()->admin()->create();
    $channel = Channel::factory()->create();

    $this->actingAs($user)
        ->get(route('channels.show', $channel))
        ->assertOk()
        ->assertInertia(
            fn (Assert $page) => $page
                ->component('channels/Show', false)
                ->where('channel.id', $channel->id)
                ->where('channel.slug', $channel->slug)
        );

    $this->actingAs($user)
        ->get(route('channels.edit', $channel))
        ->assertOk()
        ->assertInertia(
            fn (Assert $page) => $page
                ->component('channels/Edit', false)
                ->where('channel.id', $channel->id)
        );
});

test('authenticated users can update and delete channels', function () {
    $user = User::factory()->admin()->create();
    $channel = Channel::factory()->create([
        'name' => 'General Discussion',
    ]);

    $updateResponse = $this->actingAs($user)->put(route('channels.update', $channel), [
        'name' => 'Announcements',
        'description' => 'Official updates only.',
    ]);

    $updateResponse->assertRedirect(route('channels.show', $channel->fresh(), absolute: false));

    expect($channel->fresh()->name)->toBe('Announcements');
    expect($channel->fresh()->slug)->toBe('announcements');

    $deleteResponse = $this->actingAs($user)->delete(route('channels.destroy', $channel->fresh()));

    $deleteResponse->assertRedirect(route('channels.index', absolute: false));

    expect($channel->fresh())->toBeNull();
});

test('non admins cannot create, update, or delete channels via web', function () {
    $user = User::factory()->create();
    $channel = Channel::factory()->create();

    $this->actingAs($user)
        ->get(route('channels.create'))
        ->assertForbidden();

    $this->actingAs($user)
        ->post(route('channels.store'), ['name' => 'New Channel'])
        ->assertForbidden();

    $this->actingAs($user)
        ->get(route('channels.edit', $channel))
        ->assertForbidden();

    $this->actingAs($user)
        ->put(route('channels.update', $channel), ['name' => 'Renamed', 'description' => null])
        ->assertForbidden();

    $this->actingAs($user)
        ->delete(route('channels.destroy', $channel))
        ->assertForbidden();
});
