<?php

namespace Ignite\Auth\Casts;

use Ignite\Interfaces\Auth\ExpirableToken as ExpirableTokenInterface;
use Ignite\Interfaces\Auth\ExpirableTokenService;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class ExpirableTokenCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param array<string, mixed> $attributes
     */
    public function get(
        Model $model,
        string $key,
        mixed $value,
        array $attributes
    ): mixed {
        return app(ExpirableTokenService::class)->decrypt($value);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param array<string, mixed> $attributes
     */
    public function set(
        Model $model,
        string $key,
        mixed $value,
        array $attributes
    ): mixed {
        return $value = $value instanceof ExpirableTokenInterface
            ? $value->getValue()
            : $value;
    }
}
