<?php

namespace Ignite\Interfaces\Shared;

use DateTimeInterface;

interface Lockable
{
    /**
     * Lock the entity.
     *
     * @return void
     */
    public function lock(): void;

    /**
     * Unlock the entity.
     *
     * @return void
     */
    public function unlock(): void;

    /**
     * Get the time the entity was locked at.
     *
     * @return DateTimeInterface
     */
    public function wasLockedAt(): DateTimeInterface;

    /**
     * Determine if the entity is locked.
     *
     * @return bool
     */
    public function isLocked(): bool;
}
