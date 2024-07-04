<?php

namespace Ignite\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use Ignite\Auth\Http\Responses\IpAuthorisationResponse;
use Ignite\Interfaces\Auth\Ip;
use Illuminate\Contracts\Support\Responsable;
use Symfony\Component\HttpFoundation\Response;

class AuthoriseIpController extends Controller
{
    /**
     * Authorise the given IP address.
     *
     * @param \Ignite\Interfaces\Auth\Ip $ip
     *
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Support\Responsable
     */
    public function update(Ip $ip): Response|Responsable
    {
        $ip->authorise();

        return IpAuthorisationResponse::dispatch();
    }

    /**
     * Unuthorise the given IP address.
     *
     * @param \Ignite\Interfaces\Auth\Ip $ip
     *
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Support\Responsable
     */
    public function destroy(Ip $ip): Response|Responsable
    {
        $ip->unauthorise();

        return IpAuthorisationResponse::dispatch();
    }
}
