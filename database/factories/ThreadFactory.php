<?php

namespace Database\Factories;

use App\Models\Channel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Parsedown;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Thread>
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
        $parsedown = new Parsedown;

        return [
            'title' => $this->faker->sentence,
            'slug' => $this->faker->unique()->slug,
            'body' => $parsedown->text($this->faker->paragraphs(6, true)),
            'channel_id' => Channel::factory(),
            'user_id' => User::factory(),
            'locked_at' => null,
        ];
    }
}
