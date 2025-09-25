<?php

namespace Tests\Feature\Channels;

use App\Models\Channel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteChannelTest extends TestCase
{
    use RefreshDatabase;

    protected Channel $channel;

    protected function setUp(): void
    {
        parent::setUp();

        $this->channel = Channel::factory()->create([
            'name' => 'Channel to Delete',
            'description' => 'This channel will be deleted',
        ]);
    }

    public function test_delete_channel_successfully(): void
    {
        $response = $this->deleteJson("/api/channels/{$this->channel->slug}");

        $response->assertNoContent();

        $this->assertDatabaseMissing('channels', [
            'id' => $this->channel->id,
        ]);
    }

    public function test_delete_channel_using_slug(): void
    {
        $response = $this->deleteJson("/api/channels/{$this->channel->slug}");

        $response->assertNoContent();

        $this->assertDatabaseMissing('channels', [
            'id' => $this->channel->id,
        ]);
    }

    public function test_delete_nonexistent_channel_returns_404(): void
    {
        $response = $this->deleteJson('/api/channels/999');

        $response->assertNotFound();
    }

    public function test_delete_nonexistent_channel_by_slug_returns_404(): void
    {
        $response = $this->deleteJson('/api/channels/nonexistent-slug');

        $response->assertNotFound();
    }

    public function test_delete_channel_removes_from_database(): void
    {
        $channelId = $this->channel->id;

        $this->assertDatabaseHas('channels', ['id' => $channelId]);

        $this->deleteJson("/api/channels/{$this->channel->slug}");

        $this->assertDatabaseCount('channels', 0);
        $this->assertDatabaseMissing('channels', ['id' => $channelId]);
    }

    public function test_delete_multiple_channels(): void
    {
        $channel1 = Channel::factory()->create(['name' => 'Channel 1']);
        $channel2 = Channel::factory()->create(['name' => 'Channel 2']);

        $this->assertDatabaseCount('channels', 3); // Including the one from setUp

        $this->deleteJson("/api/channels/{$channel1->slug}")->assertNoContent();
        $this->deleteJson("/api/channels/{$channel2->slug}")->assertNoContent();

        $this->assertDatabaseCount('channels', 1); // Only the one from setUp remains
        $this->assertDatabaseHas('channels', ['id' => $this->channel->id]);
        $this->assertDatabaseMissing('channels', ['id' => $channel1->id]);
        $this->assertDatabaseMissing('channels', ['id' => $channel2->id]);
    }
}