<?php

namespace Tests\Feature\Threads;

use App\Enums\Role;
use App\Models\Channel;
use App\Models\Thread;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Support\Collection;

trait HandlesThreadSetup
{
    /**
     * The thread owner.
     */
    protected ?User $threadOwner = null;

    /**
     * The thread instance.
     */
    protected ?Thread $thread = null;

    /**
     * The channel instance.
     */
    protected ?Channel $channel = null;

    /**
     * The threads collection.
     */
    protected ?Collection $threads = null;

    /**
     * Build a thread by setting up all necessary dependencies.
     */
    public function setUpThread(?int $count = 1): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->threadOwner = $this->createUser(Role::ADMIN);

        $this->signIn($this->threadOwner);

        $this->channel = Channel::factory()->create();

        $this->threads = Thread::factory()
            ->count($count)
            ->for($this->threadOwner)
            ->for($this->channel)
            ->create();

        $this->thread = $this->threads->first();
    }
}
