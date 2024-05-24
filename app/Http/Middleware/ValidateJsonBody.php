<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateJsonBody
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
        if (
            $request->expectsJson()
            && ! $request->isMethod('get')
            && ! blank($request->getContent())
        ) {
            json_decode($request->getContent());

            abort_if(
                json_last_error() !== \JSON_ERROR_NONE,
                Response::HTTP_BAD_REQUEST,
                'Invalid JSON data.'
            );
        }

        return $next($request);
    }
}
