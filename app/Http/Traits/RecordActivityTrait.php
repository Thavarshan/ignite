<?php

namespace App\Http\Traits;

use App\Models\Activity;
use Illuminate\Support\Facades\Auth;

trait RecordActivityTrait
{
    /**
     * trigger this on initiating the model
     *
     */
    protected static function bootRecordActivityTrait()
    {
        if (Auth::guest()) {
            return;
        }
        foreach (self::getRecordingEvents() as $event) {
            static::created(function ($model) use ($event) {
                $model->recordActivity($event);
            });
        }
    }

    /**
     * returning array of events that can be recorded
     *
     * @return string[]
     */
    protected static function getRecordingEvents()
    {
        return ['created'];
    }

    /**
     * record an activity
     *
     * @param string $event
     */
    protected function recordActivity(string $event)
    {
        $this->activity()->create([
            'type' => $this->getActivityType($event),
            'user_id' => Auth::user()->id,
        ]);
    }

    /**
     * relation with activity class
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function activity()
    {
        return $this->morphMany(Activity::class, 'subject');
    }

    /**
     * returning activity type
     *
     * @param string $event
     *
     * @return string
     */
    protected function getActivityType(string $event): string
    {
        $type = strtolower((new \ReflectionClass($this))->getShortName());
        return $event . '_'.$type;
    }
}
