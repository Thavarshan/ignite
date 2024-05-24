<?php

namespace Ignite\Users\Http\Responses;

use App\Http\Responses\Response;
use Ignite\Interfaces\Users\UserResponse as UserResponseInterface;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class UserDeletedResponse extends Response implements UserResponseInterface
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response|\Inertia\Response
     */
    public function toResponse($request)
    {
        if ($request->hasSession()) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return $this->redirectToRoute(
            'login',
            [],
            SymfonyResponse::HTTP_SEE_OTHER
        )->banner(__('Account has been deleted.'));
    }
}
