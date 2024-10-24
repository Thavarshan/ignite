<?php

use App\Services\HashIdService;
use Hashids\HashidsInterface;

if (! function_exists('uid')) {
    /**
     * Generate a new and unique code.
     */
    function uid(?int $id = null): string|HashidsInterface
    {
        $hasher = app(HashIdService::class);

        if ($id === null) {
            return $hasher->createHasher();
        }

        return $hasher->encode($id);
    }
}
