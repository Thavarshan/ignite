<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Reply;
use App\Models\Thread;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReplyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Reply::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'body' => $this->faker->paragraph(),
            'user_id' => create(User::class)->id,
            'thread_id' => create(Thread::class)->id,
        ];
    }
}