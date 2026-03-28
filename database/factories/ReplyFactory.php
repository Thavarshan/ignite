<?php

namespace Database\Factories;

use App\Models\Reply;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Reply>
 */
class ReplyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'thread_id' => Thread::factory(),
            'user_id' => User::factory(),
            'parent_id' => null,
            'body' => fake()->paragraphs(2, true),
        ];
    }

    public function asChild(Reply $parent): static
    {
        return $this->state(fn (array $attributes) => [
            'thread_id' => $parent->thread_id,
            'parent_id' => $parent->id,
        ]);
    }
}
