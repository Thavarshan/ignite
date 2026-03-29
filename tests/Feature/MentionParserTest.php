<?php

use App\Models\User;
use App\Services\MentionParser;

test('linkify replaces a valid mention with a profile link', function () {
    $user = User::factory()->create(['name' => 'Taylor']);

    $result = app(MentionParser::class)->linkify('Hello @Taylor, welcome!');

    expect($result)->toBe("Hello [@Taylor](/users/{$user->id}), welcome!");
});

test('linkify replaces multiple mentions', function () {
    $taylor = User::factory()->create(['name' => 'Taylor']);
    $nuno = User::factory()->create(['name' => 'Nuno']);

    $result = app(MentionParser::class)->linkify('Thanks @Taylor and @Nuno!');

    expect($result)
        ->toContain("[@Taylor](/users/{$taylor->id})")
        ->toContain("[@Nuno](/users/{$nuno->id})");
});

test('linkify leaves non-existent user mentions as plain text', function () {
    $result = app(MentionParser::class)->linkify('Hey @ghost, are you there?');

    expect($result)->toBe('Hey @ghost, are you there?');
});

test('linkify returns body unchanged when no mentions exist', function () {
    $body = 'Just a normal message with no mentions.';

    $result = app(MentionParser::class)->linkify($body);

    expect($result)->toBe($body);
});

test('linkify handles mix of valid and invalid mentions', function () {
    $user = User::factory()->create(['name' => 'Ada']);

    $result = app(MentionParser::class)->linkify('Hey @Ada and @nobody!');

    expect($result)
        ->toContain("[@Ada](/users/{$user->id})")
        ->toContain('@nobody');
});

test('linkify handles duplicate mentions only once', function () {
    $user = User::factory()->create(['name' => 'Taylor']);

    $result = app(MentionParser::class)->linkify('@Taylor said hi to @Taylor');

    expect($result)->toBe("[@Taylor](/users/{$user->id}) said hi to [@Taylor](/users/{$user->id})");
});
