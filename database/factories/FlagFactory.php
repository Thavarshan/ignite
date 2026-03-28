<?php

namespace Database\Factories;

use App\Models\Flag;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Flag>
 */
class FlagFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'flaggable_id' => Thread::factory(),
            'flaggable_type' => Thread::class,
            'reason' => fake()->randomElement(['spam', 'inappropriate', 'off-topic', 'other']),
            'description' => fake()->optional()->sentence(),
        ];
    }
}
