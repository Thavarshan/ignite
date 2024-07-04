<?php

namespace App\Exceptions\Traits;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

trait HasRenderables
{
    /**
     * The renderable response to send.
     *
     * @var Closure|null
     */
    protected static ?Closure $renderable = null;

    /**
     * Render the exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response|bool
     */
    public function render(Request $request): Response|bool
    {
        if (! $request->expectsJson()) {
            return false;
        }

        return call_user_func(self::getRederable()) ?? false;
    }

    /**
     * Set the response to send.
     *
     * @param Closure $renderable
     *
     * @return void
     */
    public static function setRederable(Closure $renderable): void
    {
        self::$renderable = $renderable;
    }

    /**
     * Get the response to send.
     *
     * @return Closure|null
     */
    public static function getRederable(): ?Closure
    {
        return self::$renderable;
    }

    /**
     * Set the HTTP code to send.
     *
     * @param int $code
     *
     * @return void
     */
    public function setHttpCode(int $code = 500): void
    {
        $this->code = $code;
    }
}
