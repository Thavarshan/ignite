<?php

namespace Database\Factories;

use App\Models\Channel;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Thread>
 */
class ThreadFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'channel_id' => Channel::factory(),
            'user_id' => User::factory(),
            'title' => fake()->unique()->sentence(6),
            'body' => fake()->paragraphs(3, true),
            'locked' => false,
            'pinned' => false,
            'replies_count' => 0,
            'visits' => fake()->numberBetween(0, 500),
            'last_posted_at' => now(),
        ];
    }
}
