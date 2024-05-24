<?php

namespace Ignite\Shared\Traits;

use Carbon\Carbon;
use Closure;
use DateTimeInterface;
use Ignite\Auth\Enums\AuthEvent;
use Illuminate\Events\QueuedClosure;

trait Lockable
{
    /**
     * Lock the entity.
     *
     * @return void
     */
    public function lock(): void
    {
        $this->forceFill(['locked_at' => Carbon::now()])->save();
    }

    /**
     * Unlock the entity.
     *
     * @return void
     */
    public function unlock(): void
    {
        $this->forceFill(['locked_at' => null])->save();
    }

    /**
     * Get the time the entity was locked at.
     *
     * @return DateTimeInterface
     */
    public function wasLockedAt(): DateTimeInterface
    {
        return $this->locked_at;
    }

    /**
     * Determine if the entity is locked.
     *
     * @return bool
     */
    public function isLocked(): bool
    {
        return ! is_null($this->locked_at);
    }

    /**
     * Register a locked model event with the dispatcher.
     *
     * @param \Illuminate\Events\QueuedClosure|Closure|string|array $callback
     *
     * @return void
     */
    public static function locked(
        QueuedClosure|Closure|string|array $callback
    ): void {
        static::registerModelEvent(AuthEvent::LOCKED->value, $callback);
    }

    /**
     * Register a unlocked model event with the dispatcher.
     *
     * @param \Illuminate\Events\QueuedClosure|Closure|string|array $callback
     *
     * @return void
     */
    public static function unlocked(
        QueuedClosure|Closure|string|array $callback
    ): void {
        static::registerModelEvent(AuthEvent::UNLOCKED->value, $callback);
    }
}
