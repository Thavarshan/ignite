<?php

namespace Ignite\Activities\Models\Traits;

use Closure;
use Ignite\Activities\Enums\Event;
use Ignite\Activities\Models\Activity;
use Ignite\Auth\Facades\Auth;
use Ignite\Interfaces\Activities\Trackable as TrackableInterface;
use Ignite\Interfaces\Shared\Entity;
use Ignite\Interfaces\Users\User;
use Ignite\Shared\Data\Traits\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use ReflectionClass;

trait Trackable
{
    use Fillable;

    /**
     * Indicates if the model should record current activity.
     *
     * @var bool
     */
    protected bool $shouldTrack = true;

    /**
     * Boot the trait.
     *
     * @return void
     */
    public static function bootTrackable(): void
    {
        collect(static::getActivitiesToTrack())->each(
            fn (string $event) => static::attemptToTrackActivity($event)
        );

        static::deleting(function (TrackableInterface $entity): void {
            $entity->deleteActivities();
        });
    }

    /**
     * Fetch the activities relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function activities(): MorphMany
    {
        return $this->morphMany(Activity::class, 'subject');
    }

    /**
     * Record new event of the model.
     *
     * @param string     $event
     * @param array|null $changes
     *
     * @return void
     */
    public function trackEvent(string $event, ?array $changes = null): void
    {
        $this->recordActivity([
            'user_id' => $this->getActiveUserId(),
            'type' => $this->getType($event),
            'changes' => $changes ?? $this->getAttributeChanges(),
        ]);
    }

    /**
     * Record new activity for the model.
     *
     * @param array $data
     *
     * @return void
     */
    public function recordActivity(array $data): void
    {
        $this->activities()->create(
            $this->filterFillable($data, Activity::class)
        );
    }

    /**
     * Get the user ID of the currently authenticated user.
     *
     * @return int|null
     */
    public function getActiveUserId(): ?int
    {
        if (Auth::hasUser()) {
            return Auth::id();
        }

        if ($this instanceof User) {
            return $this->getId();
        }

        return null;
    }

    /**
     * Determine if activity recording is enabled.
     *
     * @param string $event
     *
     * @return bool
     */
    public function activityIsTrackable(string $event): bool
    {
        return $this->exists
            && $this->getActiveUserId()
            && in_array($event, static::getActivitiesToTrack())
            // Don't record activity for the user who is being created or deleted.
            && ! (($event === Event::DELETED->value || $event === Event::CREATED->value) // phpcs:ignore
                && $this->getId() === $this->getActiveUserId()
                && $this instanceof User);
    }

    /**
     * Determine the activity type.
     *
     * @param string $event
     *
     * @return string
     */
    public function getType(string $event): string
    {
        return with(strtolower(
            static::reflection()->getShortName()
        ), function (string $type) use ($event) {
            return sprintf('%s_%s', $type, Str::snake($event));
        });
    }

    /**
     * Determine if the model should record activity.
     *
     * @return bool
     */
    public function getShouldTrack(): bool
    {
        return $this->shouldTrack;
    }

    /**
     * Set the model to record activity.
     *
     * @return \Illuminate\Database\Eloquent\Model|\Ignite\Interfaces\Shared\Entity
     */
    public function track(): Model|Entity
    {
        $this->shouldTrack = true;

        return $this;
    }

    /**
     * Set the model to not record activity.
     *
     * @return \Illuminate\Database\Eloquent\Model|\Ignite\Interfaces\Shared\Entity
     */
    public function doNotTrack(): Model|Entity
    {
        $this->shouldTrack = false;

        return $this;
    }

    /**
     * Perform the given callback without recording activity.
     *
     * @param Closure $callback
     *
     * @return void
     */
    public function withoutTracking(Closure $callback): void
    {
        $this->doNotTrack();

        try {
            tap($this, $callback);
        } finally {
            $this->track();
        }
    }

    /**
     * Delete all activities of the model.
     *
     * @return void
     */
    public function deleteActivities(): void
    {
        $this->activities()->delete();
    }

    /**
     * Attempt to record activity for the model.
     *
     * @param string $event
     *
     * @return void
     */
    protected static function attemptToTrackActivity(string $event): void
    {
        if (method_exists(static::class, $event)) {
            static::$event(function (TrackableInterface $entity) use ($event): void {
                if (
                    ! $entity->activityIsTrackable($event)
                    || ! $entity->getShouldTrack()
                ) {
                    return;
                }

                $entity->trackEvent($event);
            });
        }
    }

    /**
     * Get a reflection of this class.
     *
     * @return ReflectionClass
     */
    protected static function reflection(): ReflectionClass
    {
        return new ReflectionClass(new self());
    }

    /**
     * Fetch all model events that require activity recording.
     *
     * @return array<string>
     */
    protected static function getActivitiesToTrack(): array
    {
        if (method_exists(static::class, 'defineRecordableEvents')) {
            return call_user_func([static::class, 'defineRecordableEvents']);
        }

        return collect(Event::defaults())
            ->merge(static::reflection()->getStaticPropertyValue('recordableEvents', [])) // phpcs:ignore
            ->filter()
            ->unique()
            ->toArray();
    }

    /**
     * Fetch the changes to the model.
     *
     * @return array|null
     */
    protected function getAttributeChanges(): ?array
    {
        if ($this->wasChanged()) {
            return [
                'before' => Arr::except(
                    array_diff($this->getRawOriginal(), $this->getAttributes()),
                    'updated_at'
                ),
                'after' => Arr::except(
                    $this->getChanges(),
                    'updated_at'
                ),
            ];
        }

        return null;
    }
}
