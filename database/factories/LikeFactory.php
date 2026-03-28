<?php

namespace Database\Factories;

use App\Models\Channel;
use App\Models\Like;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Like>
 */
class LikeFactory extends Factory
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
            'likeable_id' => Thread::factory()->for(Channel::factory())->for(User::factory()),
            'likeable_type' => Thread::class,
        ];
    }
}
