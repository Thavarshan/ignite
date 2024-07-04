<?php

namespace Ignite\Auth\Actions\Phases;

use Closure;
use Ignite\Auth\Events\IpNotAuthorised;
use Ignite\Auth\Exceptions\AuthenticationException;
use Ignite\Auth\Features\IpAuthorisation;
use Ignite\Interfaces\Auth\AuthenticationRequest;
use Ignite\Interfaces\Auth\ValidatesIpAuthorisation;
use Ignite\Interfaces\Users\User;
use Ignite\Organisations\Models\Organisation;
use Illuminate\Http\Request;
use Laravel\Pennant\Feature;

class CheckIpAuthorisation extends AuthAction
{
    /**
     * Handle the incoming request.
     *
     * @param \Ignite\Interfaces\Auth\AuthenticationRequest|\Illuminate\Http\Request $request
     * @param Closure                                                                $next
     *
     * @return mixed
     */
    public function handle(
        AuthenticationRequest|Request $request,
        Closure $next
    ): mixed {
        $user = $this->getAttemptingUser() ?? $this->validateCredentials($request);

        if (! Feature::for($user)->active(IpAuthorisation::class)) {
            return $next($request);
        }

        tap($this->make(
            ValidatesIpAuthorisation::class
        ), function ($validator) use ($request, $user) {
            $ip = $request->ip();

            try {
                $validator->validate(
                    $ip,
                    $this->getUserOrganisationIpRanges($user)
                );
            } catch (AuthenticationException $e) {
                $this->handleUnauthorisedIp($request, $user, $ip);

                throw $e;
            }
        });

        return $next($request);
    }

    /**
     * Handle unauthorised IP address.
     *
     * @param \Ignite\Interfaces\Auth\AuthenticationRequest|\Illuminate\Http\Request $request
     * @param \Ignite\Interfaces\Users\User                                          $user
     * @param string                                                                 $ip
     *
     * @return void
     */
    protected function handleUnauthorisedIp(
        AuthenticationRequest|Request $request,
        User $user,
        string $ip
    ): void {
        $user->createUnauthorisedIp($ip);

        $user->load('organisation');

        IpNotAuthorised::dispatch($user);
    }

    /**
     * Get the IP ranges for the given user's organisation settings.
     *
     * @param \Ignite\Interfaces\Users\User $user
     *
     * @return array
     */
    protected function getUserOrganisationIpRanges(User $user): array
    {
        return transform($user->getOrganisation(), function (Organisation $organisation) {
            return settings($organisation, 'features.ip_authorisation.ranges');
        });
    }
}
