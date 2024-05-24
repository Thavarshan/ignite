<?php

namespace App\Http\Responses;

use Closure;
use Illuminate\Contracts\Support\Responsable;

class SimpleResponse extends Response implements Responsable
{
    /**
     * Make a simple response by executing a callback and producing it's result.
     *
     * @param Closure $callback
     *
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Support\Responsable
     */
    public static function as(Closure $callback): Response|Responsable
    {
        return tap(null, $callback);
    }

    /**
     * Create an HTTP response that represents the object.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        if (is_string($this->content)) {
            $response = $request->wantsJson()
                ? $this->json($this->content)
                : $this->make($this->content);
        } elseif ($this->content instanceof Closure) {
            $response = call_user_func($this->content, $request);
        } else {
            $response = $this->make();
        }

        if ($response instanceof Responsable) {
            return $response->toResponse($request);
        }

        return $response;
    }
}
