<?php

namespace Ignite\Auth\Actions\Phases;

use Ignite\Interfaces\Auth\AuthenticationRequest;
use Laravel\Fortify\Events\TwoFactorAuthenticationChallenged;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfTwoFactorAuthenticatable extends AuthAction
{
    /**
     * Handle the incoming request.
     *
     * @param \Ignite\Interfaces\Auth\AuthenticationRequest $request
     * @param callable                                      $next
     *
     * @return mixed
     */
    public function handle(AuthenticationRequest $request, $next)
    {
        $user = $this->getAttemptingUser() ?? $this->validateCredentials($request);

        if (
            optional($user)->two_factor_secret
            && ! is_null(optional($user)->two_factor_confirmed_at)
            && in_array(TwoFactorAuthenticatable::class, class_uses_recursive($user))
        ) {
            return $this->twoFactorChallengeResponse($request, $user);
        }

        return $next($request);
    }

    /**
     * Get the two factor authentication enabled response.
     *
     * @param \Illuminate\Http\Request $request
     * @param mixed                    $user
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function twoFactorChallengeResponse($request, $user)
    {
        $request->session()->put([
            'login.id' => $user->getKey(),
            'login.remember' => $request->boolean('remember'),
        ]);

        TwoFactorAuthenticationChallenged::dispatch($user);

        return $request->wantsJson()
            ? response()->json(['two_factor' => true])
            : redirect(route('two-factor.login'), Response::HTTP_SEE_OTHER);
    }
}
