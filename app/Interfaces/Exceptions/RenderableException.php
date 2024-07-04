<?php

namespace App\Interfaces\Exceptions;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

interface RenderableException
{
    /**
     * Render the exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response|bool
     */
    public function render(Request $request): Response|bool;

    /**
     * Set the response to send.
     *
     * @param Closure $renderable
     *
     * @return void
     */
    public static function setRederable(Closure $renderable): void;

    /**
     * Get the response to send.
     *
     * @return Closure|null
     */
    public static function getRederable(): ?Closure;
}
