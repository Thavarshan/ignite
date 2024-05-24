<?php

namespace Ignite\Interfaces\Shared;

interface Hashable
{
    /**
     * Generate a new and unique code
     *
     * @return void
     */
    public function generateHashId(): void;

    /**
     * Get the entity's unique identifier prefix.
     *
     * @return string
     */
    public function getPrefix(): string;

    /**
     * Get the entity's unique identifier.
     *
     * @return string|null
     */
    public function getUid(): ?string;

    /**
     * Get the entity's unique identifier.
     *
     * @return string
     */
    public function revealHashedId(): string;
}
