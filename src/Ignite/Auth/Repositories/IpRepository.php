<?php

namespace Ignite\Auth\Repositories;

use Ignite\Interfaces\Auth\Ip;
use Ignite\Interfaces\Auth\IpRepository as IpRepositoryInterface;
use Ignite\Shared\Data\Repositories\Repository;

class IpRepository extends Repository implements IpRepositoryInterface
{
    /**
     * Create a new IpRepository instance.
     *
     * @param \Ignite\Interfaces\Auth\Ip $ip
     *
     * @return void
     */
    public function __construct(Ip $ip)
    {
        $this->model = $ip;
    }
}
