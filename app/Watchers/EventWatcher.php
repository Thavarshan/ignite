<?php

namespace App\Watchers;

use App\Enums\Entry;
use App\Support\ExtractProperties;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;

class EventWatcher extends Watcher
{
    /**
     * Register the watcher.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     *
     * @return void
     */
    public function register(Application $app): void
    {
        $app['events']->listen('*', [$this, 'recordEvent']);
    }

    /**
     * Record an event.
     *
     * @param string $event
     * @param array  $payload
     *
     * @return void
     */
    public function recordEvent(string $event, array $payload): void
    {
        $formattedPayload = $this->extractPayload($event, $payload);

        $this->record(
            Entry::EVENT,
            IncomingEntry::new([
                'properties' => blank($formattedPayload) ? null : $formattedPayload,
                'aggregate_uuid' => $this->runtimeKey->value(),
                'name' => $event,
                'meta' => [
                    'created_at' => Carbon::now()->toDateTimeString(),
                ],
            ])
        );
    }

    /**
     * Extract the payload and tags from the event.
     *
     * @param string $event
     * @param array  $payload
     *
     * @return array
     */
    protected function extractPayload(string $event, array $payload): array
    {
        if (class_exists($event) && isset($payload[0]) && is_object($payload[0])) {
            return ExtractProperties::from($payload[0]);
        }

        return collect($payload)->map(function ($value) {
            return is_object($value) ? [
                'class' => $value::class,
                'properties' => json_decode(json_encode($value), true),
            ] : $value;
        })->toArray();
    }
}
