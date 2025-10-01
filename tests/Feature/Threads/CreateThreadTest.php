<?php

namespace Tests\Feature\Threads;

use App\Models\Channel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class CreateThreadTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_thread_creation(): void
    {
        $this->get(route('threads.create'))->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_thread_creation_form(): void
    {
        Channel::factory()->count(2)->create();
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('threads.create'))
            ->assertStatus(200)
            ->assertInertia(
                fn (Assert $page): Assert => $page
                    ->component('threads/Create')
                    ->has('channels', Channel::count())
            );
    }
}
