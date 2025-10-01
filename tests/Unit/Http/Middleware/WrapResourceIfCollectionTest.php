<?php

namespace Tests\Unit\Http\Middleware;

use App\Http\Middleware\WrapResourceIfCollection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class WrapResourceIfCollectionTest extends TestCase
{
    public function test_it_wraps_numeric_collections(): void
    {
        $middleware = new WrapResourceIfCollection;
        $request = Request::create('/api/threads', 'GET');

        $response = $middleware->handle($request, fn () => new JsonResponse([
            ['id' => 1],
            ['id' => 2],
        ]));

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(['data' => [
            ['id' => 1],
            ['id' => 2],
        ]], $response->getData(true));
    }

    public function test_it_leaves_associative_responses_unwrapped(): void
    {
        $middleware = new WrapResourceIfCollection;
        $request = Request::create('/api/threads/1', 'GET');

        $response = $middleware->handle($request, fn () => new JsonResponse([
            'id' => 1,
            'title' => 'First thread',
        ]));

        $this->assertSame([
            'id' => 1,
            'title' => 'First thread',
        ], $response->getData(true));
    }

    public function test_it_ignores_non_json_responses(): void
    {
        $middleware = new WrapResourceIfCollection;
        $request = Request::create('/web', 'GET');
        $expected = new Response('ok');

        $response = $middleware->handle($request, fn () => $expected);

        $this->assertSame($expected, $response);
    }
}
