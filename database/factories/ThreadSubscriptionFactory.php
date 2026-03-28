<?php

namespace Database\Factories;

use App\Models\Channel;
use App\Models\Thread;
use App\Models\ThreadSubscription;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ThreadSubscription>
 */
class ThreadSubscriptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'thread_id' => Thread::factory()->for(Channel::factory())->for(User::factory()),
        ];
    }
}
