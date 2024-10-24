<?php

namespace App\Services;

use Hashids\Hashids;
use Hashids\HashidsInterface;

class HashIdService
{
    /**
     * Default UID character length.
     */
    public const CHARACTER_LENGTH = 7;

    /**
     * String of acceptable characters.
     *
     * @var string
     */
    protected static $characterPool = 'ABCDEFGHJKLMNOPQRSTUVWXYZ23456789';

    /**
     * Generate a new and unique code.
     */
    public function encode(int $id): string
    {
        return $this->createHasher()->encode($id);
    }

    /**
     * Decode the given hash.
     *
     *
     * @return array<int>
     */
    public function decode(string $hash): array
    {
        return $this->createHasher()->decode($hash);
    }

    /**
     * Create Hashids instance.
     */
    public function createHasher(?string $key = null): HashidsInterface
    {
        return new Hashids(
            salt: $key ? $key : config('app.key', $key),
            minHashLength: self::CHARACTER_LENGTH,
            alphabet: static::$characterPool
        );
    }

    /**
     * Get the character pool.
     */
    public static function getCharacterPool(): string
    {
        return static::$characterPool;
    }
}
