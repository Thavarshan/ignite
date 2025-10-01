<?php

namespace Tests\Feature\Database;

use App\Models\Thread;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\LazyLoadingViolationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseGuardRailsTest extends TestCase
{
    use RefreshDatabase;

    public function test_lazy_loading_is_prevented(): void
    {
        $this->assertTrue(Model::preventsLazyLoading());

        Thread::factory()->count(2)->create();

        $thread = Thread::query()->limit(2)->get()->first();

        $this->expectException(LazyLoadingViolationException::class);

        $thread->channel->name;
    }
}
