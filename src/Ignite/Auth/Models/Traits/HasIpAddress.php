<?php

namespace Ignite\Auth\Models\Traits;

use Ignite\Auth\Models\Ip;
use Ignite\Interfaces\Auth\Ip as IpInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait HasIpAddress
{
    /**
     * Get the user's Ip address.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function ip(): HasOne
    {
        return $this->hasOne(Ip::class);
    }

    /**
     * Get the user's Ip address.
     *
     * @return \Ignite\Interfaces\Auth\Ip|null
     */
    public function getIp(): ?IpInterface
    {
        return $this->ip;
    }

    /**
     * Save but not authorise the ip address this user is logging in from.
     *
     * @param string $ip
     *
     * @return \Illuminate\Database\Eloquent\Model|\Ignite\Interfaces\Auth\Ip
     */
    public function createUnauthorisedIp(string $ip): Model|IpInterface
    {
        return $this->createIp($ip, false);
    }

    /**
     * Save and authorise the ip address this user is logging in from.
     *
     * @param string $ip
     *
     * @return \Illuminate\Database\Eloquent\Model|\Ignite\Interfaces\Auth\Ip
     */
    public function createAuthorisedIp(string $ip): Model|IpInterface
    {
        return $this->createIp($ip, true);
    }

    /**
     * Save the ip address this user is logging in from.
     *
     * @param string $ip
     * @param bool   $authorised
     *
     * @return \Illuminate\Database\Eloquent\Model|\Ignite\Interfaces\Auth\Ip
     */
    public function createIp(
        string $ip,
        bool $authorised = false
    ): Model|IpInterface {
        $ip = $this->ip()->updateOrCreate(
            attributes: compact('ip'),
            values: compact('ip'),
        );

        if ($authorised) {
            $ip->authorise();
        }

        return $ip;
    }

    /**
     * Authorise the ip address of this user.
     *
     * @return void
     */
    public function authoriseIp(): void
    {
        $this->ip->authorise();
    }

    /**
     * Unauthorise the ip address of this user.
     *
     * @return void
     */
    public function unauthoriseIp(): void
    {
        $this->ip->unauthorise();
    }
}
