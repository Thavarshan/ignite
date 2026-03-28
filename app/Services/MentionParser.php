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
}
