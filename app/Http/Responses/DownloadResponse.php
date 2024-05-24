<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class DownloadResponse extends Response
{
    /**
     * Dispatch response.
     *
     * @param mixed|null  $content
     * @param int|int     $status
     * @param string|null $viewName
     * @param string|null $message
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Support\Responsable|static
     */
    public static function dispatch(
        mixed $content = null,
        ?int $status = SymfonyResponse::HTTP_OK,
        ?string $viewName = null,
        ?string $message = null
    ): SymfonyResponse|RedirectResponse|JsonResponse|Responsable|static {
        /** @var \Illuminate\Contracts\Support\Responsable|static $response */
        $response = static::create($content, $viewName, $message);

        return $response->toResponse(request());
    }
}
