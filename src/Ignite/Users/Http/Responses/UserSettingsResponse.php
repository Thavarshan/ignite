<?php

namespace Ignite\Users\Http\Responses;

use App\Http\Responses\Response;
use Ignite\Interfaces\Users\UserSettingsResponse as UserSettingsResponseInterface;
use Ignite\Shared\Enums\Resource;

class UserSettingsResponse extends Response implements UserSettingsResponseInterface
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response|\Inertia\Response
     *
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter
     */
    public function toResponse($request)
    {
        return $this->back()->banner(
            __('messages.resource.saved', [
                'resource' => Resource::SETTINGS->value,
            ])
        );
    }
}
