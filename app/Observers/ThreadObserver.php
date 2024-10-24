<?php

namespace App\Observers;

use App\Models\Thread;
use App\Services\AgentHandlerService;

class ThreadObserver
{
    /**
     * Handle the Thread "created" event.
     *
     * @return void
     */
    public function created(Thread $thread)
    {
        if (! $thread->isTicket()) {
            return;
        }

        $agent = app(AgentHandlerService::class)->getNextAvailableAgent();

        if (! $agent) {
            return;
        }

        $thread->assignTo($agent);
    }

    /**
     * Handle the Thread "updated" event.
     *
     * @return void
     */
    public function updated(Thread $thread)
    {
        //
    }

    /**
     * Handle the Thread "deleted" event.
     *
     * @return void
     */
    public function deleted(Thread $thread)
    {
        //
    }

    /**
     * Handle the Thread "restored" event.
     *
     * @return void
     */
    public function restored(Thread $thread)
    {
        //
    }

    /**
     * Handle the Thread "force deleted" event.
     *
     * @return void
     */
    public function forceDeleted(Thread $thread)
    {
        //
    }
}
