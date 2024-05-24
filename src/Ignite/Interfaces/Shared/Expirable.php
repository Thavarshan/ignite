<?php

namespace Ignite\Interfaces\Shared;

use Carbon\CarbonInterface;

interface Expirable
{
    /**
     * Get the expiration timestamp.
     *
     * @return \Carbon\CarbonInterface
     */
    public function getExpiredAt(): CarbonInterface;

    /**
     * Set the expiration timestamp.
     *
     * @param \Carbon\CarbonInterface $expiredAt
     *
     * @return void
     */
    public function setExpiredAt(CarbonInterface $expiredAt): void;

    /**
     * Determine if the entity has expired.
     *
     * @return bool
     */
    public function isExpired(): bool;
}
