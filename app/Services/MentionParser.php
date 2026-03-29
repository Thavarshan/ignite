<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Collection;

class MentionParser
{
    /**
     * Extract mentioned users from body text.
     *
     * @return Collection<int, User>
     */
    public function parse(string $body): Collection
    {
        if (! preg_match_all('/@([\w-]+)/', $body, $matches)) {
            return collect();
        }

        $names = collect($matches[1])->unique()->values();

        return User::query()->whereIn('name', $names)->get();
    }

    /**
     * Replace @mentions with markdown profile links.
     */
    public function linkify(?string $body): string
    {
        if ($body === null || $body === '') {
            return $body ?? '';
        }

        if (! preg_match_all('/@([\w-]+)/', $body, $matches)) {
            return $body;
        }

        $names = collect($matches[1])->unique()->values();
        $users = User::query()->whereIn('name', $names)->get()->keyBy('name');

        return (string) preg_replace_callback('/@([\w-]+)/', function (array $match) use ($users) {
            $user = $users->get($match[1]);

            return $user ? "[@{$user->name}](/users/{$user->id})" : $match[0];
        }, $body);
    }
}
