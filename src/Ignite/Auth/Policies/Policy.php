<?php

namespace Ignite\Auth\Policies;

use Ignite\Auth\Enums\Permission;
use Ignite\Shared\Enums\Action;
use Ignite\Shared\Enums\Resource;

abstract class Policy
{
    /**
     * The resource the policy corresponds to.
     *
     * @var \Ignite\Shared\Enums\Resource
     */
    protected $resource;

    /**
     * Get the resource the policy corresponds to.
     *
     * @return \Ignite\Shared\Enums\Resource
     */
    public function getResource(): Resource
    {
        return $this->resource;
    }

    /**
     * Get the permission for the given action on the given resource.
     *
     * @param \Ignite\Shared\Enums\Action   $action
     * @param \Ignite\Shared\Enums\Resource $on
     *
     * @return string
     */
    public function do(Action $action, Resource $on): string
    {
        return Permission::to($action, $on);
    }
}
