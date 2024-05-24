<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BlockJsonRequests
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request                                                        $request
     * @param Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        abort_if(
            $request->acceptsJson() && $request->expectsJson(),
            Response::HTTP_FORBIDDEN,
            'JSON requests are not allowed.'
        );

        return $next($request);
    }
}
