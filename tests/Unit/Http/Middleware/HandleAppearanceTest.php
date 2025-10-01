<?php

namespace Tests\Unit\Http\Middleware;

use App\Http\Middleware\HandleAppearance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class HandleAppearanceTest extends TestCase
{
    protected function tearDown(): void
    {
        View::clearResolvedInstances();

        parent::tearDown();
    }

    public function test_it_shares_cookie_value_with_views(): void
    {
        View::spy();

        $middleware = new HandleAppearance;
        $request = Request::create('/settings', 'GET');
        $request->cookies->set('appearance', 'dark');

        $response = $middleware->handle($request, fn () => new Response('ok'));

        View::shouldHaveReceived('share')->with('appearance', 'dark');
        $this->assertSame('ok', $response->getContent());
    }

    public function test_it_defaults_to_system_when_cookie_is_missing(): void
    {
        View::spy();

        $middleware = new HandleAppearance;
        $request = Request::create('/settings', 'GET');

        $middleware->handle($request, fn () => new Response('ok'));

        View::shouldHaveReceived('share')->with('appearance', 'system');
    }
}
