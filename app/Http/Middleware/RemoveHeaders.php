<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RemoveHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param Closure                  $next
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        /**
         * @var \Symfony\Component\HttpFoundation\Response $response
         */
        $response = $next($request);

        foreach ((array) config('headers.remove') as $header) {
            header_remove($header);

            $response->headers->remove(
                key: $header,
            );
        }

        return $response;
    }
}
