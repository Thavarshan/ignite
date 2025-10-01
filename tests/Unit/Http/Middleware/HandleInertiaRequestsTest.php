<?php

namespace Tests\Unit\Http\Middleware;

use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Http\Request;
use Tests\TestCase;

class HandleInertiaRequestsTest extends TestCase
{
    public function test_it_shares_application_defaults(): void
    {
        $middleware = new HandleInertiaRequests;
        $request = Request::create('/', 'GET');

        $shared = $middleware->share($request);

        $this->assertSame(config('app.name'), $shared['name']);
        $this->assertArrayHasKey('errors', $shared);
        $this->assertArrayHasKey('quote', $shared);
        $this->assertArrayHasKey('message', $shared['quote']);
        $this->assertArrayHasKey('author', $shared['quote']);
        $this->assertTrue($shared['sidebarOpen']);
    }

    public function test_it_uses_sidebar_cookie_when_present(): void
    {
        $middleware = new HandleInertiaRequests;
        $request = Request::create('/', 'GET');
        $request->cookies->set('sidebar_state', 'false');

        $shared = $middleware->share($request);

        $this->assertFalse($shared['sidebarOpen']);
    }
}
