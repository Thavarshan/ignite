<?php

use App\Models\User;
use App\Services\MentionParser;

test('it extracts mentioned users from text', function () {
    $user = User::factory()->create(['name' => 'taylor']);
    User::factory()->create(['name' => 'nuno']);

    $result = (new MentionParser)->parse('Hello @taylor, have you seen this?');

    expect($result)->toHaveCount(1)
        ->and($result->first()->id)->toBe($user->id);
});

test('it extracts multiple mentioned users', function () {
    User::factory()->create(['name' => 'taylor']);
    User::factory()->create(['name' => 'nuno']);

    $result = (new MentionParser)->parse('Hey @taylor and @nuno!');

    expect($result)->toHaveCount(2);
});

test('it ignores non-existent users', function () {
    User::factory()->create(['name' => 'taylor']);

    $result = (new MentionParser)->parse('Hey @taylor and @ghost');

    expect($result)->toHaveCount(1);
});

test('it returns empty collection when no mentions', function () {
    $result = (new MentionParser)->parse('No mentions here.');

    expect($result)->toBeEmpty();
});

test('it deduplicates repeated mentions', function () {
    User::factory()->create(['name' => 'taylor']);

    $result = (new MentionParser)->parse('@taylor said @taylor again');

    expect($result)->toHaveCount(1);
});

test('it handles usernames with hyphens', function () {
    $user = User::factory()->create(['name' => 'joe-doe']);

    $result = (new MentionParser)->parse('Cc @joe-doe');

    expect($result)->toHaveCount(1)
        ->and($result->first()->id)->toBe($user->id);
});
