<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WrapResourceIfCollection
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($response instanceof JsonResponse) {
            $data = $response->getData(true);

            // Check if this looks like a collection response that should be wrapped
            if ($this->shouldWrapResponse($data)) {
                $response->setData(['data' => $data]);
            }
        }

        return $response;
    }

    /**
     * Determine if the response data should be wrapped.
     */
    protected function shouldWrapResponse(array $data): bool
    {
        return array_keys($data) === range(0, count($data) - 1);
    }
}
