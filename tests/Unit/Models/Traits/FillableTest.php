<?php

namespace Tests\Unit\Models\Traits;

use App\Models\Thread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FillableTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_filters_fillable_attributes_using_default_model(): void
    {
        $payload = [
            'title' => 'First thread',
            'body' => '<p>Body</p>',
            'channel_id' => 1,
            'user_id' => 1,
            'unexpected' => 'value',
        ];

        $result = Thread::filterFillable($payload);

        $this->assertArrayNotHasKey('unexpected', $result);
        $this->assertSame('First thread', $result['title']);
        $this->assertSame('<p>Body</p>', $result['body']);
    }

    public function test_it_filters_fillable_attributes_using_provided_resource_class(): void
    {
        $payload = [
            'title' => 'Second thread',
            'body' => '<p>Body</p>',
            'channel_id' => 1,
            'user_id' => 1,
            'locked_at' => now(),
            'unexpected' => 'value',
        ];

        $result = Thread::filterFillable($payload, Thread::class);

        $this->assertArrayHasKey('locked_at', $result);
        $this->assertArrayNotHasKey('unexpected', $result);
    }
}
