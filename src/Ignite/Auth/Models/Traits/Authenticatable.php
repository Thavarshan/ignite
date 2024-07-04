<?php

namespace Ignite\Auth\Models\Traits;

use Carbon\Carbon;
use Closure;
use Ignite\Auth\Enums\AuthEvent;
use Ignite\Auth\Models\Session;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Events\QueuedClosure;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Laravel\Jetstream\Agent;

trait Authenticatable
{
    use HasIpAddress;

    /**
     * The events to record activity for.
     *
     * @var array<int, string>
     */
    protected static $recordableEvents = [
        AuthEvent::LOGGED_IN->value,
        AuthEvent::LOGGED_OUT->value,
    ];

    /**
     * Get the user's sessions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sessions(): HasMany
    {
        return $this->hasMany(Session::class);
    }

    /**
     * Get the current sessions.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Support\Collection
     */
    public function getSessions(Request $request): Collection
    {
        if (config('session.driver') !== 'database') {
            return collect();
        }

        return collect(
            $this->sessions()
                ->orderBy('last_activity', 'desc')
                ->get()
        )->map(function ($session) use ($request) {
            $agent = $this->createAgent($session);

            return (object) [
                'agent' => [
                    'is_desktop' => $agent->isDesktop(),
                    'platform' => $agent->platform(),
                    'browser' => $agent->browser(),
                ],
                'ip_address' => $session->ip_address,
                'is_current_device' => $session->id === $request->session()->getId(),
                'last_active' => Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
            ];
        });
    }

    /**
     * Register a loggedIn model event with the dispatcher.
     *
     * @param \Illuminate\Events\QueuedClosure|Closure|string|array $callback
     *
     * @return void
     */
    public static function loggedIn(
        QueuedClosure|Closure|string|array $callback
    ): void {
        static::registerModelEvent(AuthEvent::LOGGED_IN->value, $callback);
    }

    /**
     * Register a loggedOut model event with the dispatcher.
     *
     * @param \Illuminate\Events\QueuedClosure|Closure|string|array $callback
     *
     * @return void
     */
    public static function loggedOut(
        QueuedClosure|Closure|string|array $callback
    ): void {
        static::registerModelEvent(AuthEvent::LOGGED_OUT->value, $callback);
    }

    /**
     * Fire successful authentication event.
     *
     * @return void
     */
    public function fireLoggedInEvent(): void
    {
        $this->fireModelEvent(AuthEvent::LOGGED_IN->value, false);
    }

    /**
     * Fire successfully unauthenticated event.
     *
     * @return void
     */
    public function fireLoggedOutEvent(): void
    {
        $this->fireModelEvent(AuthEvent::LOGGED_OUT->value, false);
    }

    /**
     * Create a new agent instance from the given session.
     *
     * @param mixed $session
     *
     * @return \Laravel\Jetstream\Agent
     */
    protected function createAgent(mixed $session): Agent
    {
        return tap(new Agent(), fn ($agent) => $agent->setUserAgent($session->user_agent));
    }
}
