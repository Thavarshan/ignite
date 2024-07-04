<?php

namespace Ignite\Auth\Features;

use Ignite\Interfaces\Users\User;

class IpAuthorisation
{
    /**
     * Resolve the feature's initial value.
     *
     * @param \Ignite\Interfaces\Users\User $user
     *
     * @return mixed
     */
    public function resolve(User $user): mixed
    {
        return settings(
            entity: $user->getOrganisation(),
            key: 'features.ip_authorisation.enabled',
            default: false
        );
    }
}
