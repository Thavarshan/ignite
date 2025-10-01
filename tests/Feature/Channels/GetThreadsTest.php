<?php

namespace Tests\Feature\Channels;

use App\Models\Channel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Illuminate\Testing\Fluent\AssertableJson;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class GetThreadsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * The channels collection.
     */
    protected Collection $channels;

    protected function setUp(): void
    {
        parent::setUp();

        $this->channels = collect([
            Channel::factory()->create(['name' => 'Laravel Framework']),
            Channel::factory()->create(['name' => 'Vue.js']),
            Channel::factory()->create(['name' => 'React']),
        ]);
    }

    public function test_get_all_channels(): void
    {
        $response = $this->get('/api/channels');

        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data');
        $response->assertJsonFragment([
            'name' => $this->channels->first()->name,
            'slug' => $this->channels->first()->slug,
        ]);
    }

    public function test_filter_thread_by_name(): void
    {
        $response = $this->get('/api/channels?name='.$this->channels->first()->name);

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonFragment([
            'name' => $this->channels->first()->name,
            'slug' => $this->channels->first()->slug,
        ]);
    }

    public function test_get_single_channel(): void
    {
        $channel = $this->channels->first();

        $this->get('/channels/'.$channel->slug)
            ->assertInertia(
                fn (Assert $page): AssertableJson => $page
                    ->component('channels/Show')
                    ->has(
                        'channel',
                        fn (Assert $page): AssertableJson => $page
                            ->where('id', $channel->id)
                            ->where('name', $channel->name)
                            ->where('description', $channel->description)
                            ->where('slug', $channel->slug)
                            ->has('created_at')
                            ->has('updated_at')
                    )
            );
    }
}
