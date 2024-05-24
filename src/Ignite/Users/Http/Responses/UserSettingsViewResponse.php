<?php

namespace Ignite\Users\Http\Responses;

use App\Http\Responses\Response;
use Ignite\Interfaces\Users\UserSettingsViewResponse as UserSettingsViewResponseInterface;

class UserSettingsViewResponse extends Response implements UserSettingsViewResponseInterface
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
        return $this->renderView('Settings/Index');
    }
}
