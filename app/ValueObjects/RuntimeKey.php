<?php

namespace App\ValueObjects;

class RuntimeKey
{
    /**
     * Create a new RuntimeKey instance.
     *
     * @param string $key
     *
     * @return void
     */
    public function __construct(protected string $key)
    {
    }

    /**
     * Get the runtime key.
     *
     * @return string
     */
    public function value(): string
    {
        return $this->key;
    }
}
