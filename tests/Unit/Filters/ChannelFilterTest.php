<?php

namespace Tests\Unit\Filters;

use App\Filters\ChannelFilter;
use App\Models\Channel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class ChannelFilterTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_filters_by_name(): void
    {
        $matching = Channel::factory()->create(['name' => 'General Discussion']);
        Channel::factory()->create(['name' => 'Announcements']);

        $request = Request::create('/', 'GET', ['name' => 'General Discussion']);
        $filter = new ChannelFilter($request);

        $channels = Channel::query()->filter($filter)->get();

        $this->assertCount(1, $channels);
        $this->assertTrue($channels->first()->is($matching));
    }
}
