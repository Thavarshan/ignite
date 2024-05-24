<?php

namespace Ignite\Shared\Support;

use Hashids\Hashids;
use Hashids\HashidsInterface;

class HashId
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
     *
     * @param int $id
     *
     * @return string
     */
    public static function generate(int $id): string
    {
        return static::createHasher()->encode($id);
    }

    /**
     * Decode the given hash.
     *
     * @param string $hash
     *
     * @return array<int>
     */
    public static function decode(string $hash): array
    {
        return static::createHasher()->decode($hash);
    }

    /**
     * Create Hashids instance.
     *
     * @param string|null $key
     *
     * @return \Hashids\HashidsInterface
     */
    public static function createHasher(?string $key = null): HashidsInterface
    {
        return new Hashids(
            salt: $key ? $key : config('app.key', $key),
            minHashLength: self::CHARACTER_LENGTH,
            alphabet: static::$characterPool
        );
    }
}
