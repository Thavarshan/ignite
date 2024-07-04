<?php

namespace Ignite\Auth\Repositories;

use Ignite\Interfaces\Auth\Role;
use Ignite\Interfaces\Auth\RoleRepository as RoleRepositoryInterface;
use Ignite\Interfaces\Shared\Entity;
use Ignite\Shared\Data\Repositories\Repository;
use Illuminate\Database\Eloquent\Model;

class RoleRepository extends Repository implements RoleRepositoryInterface
{
    /**
     * Create a new repository instance.
     *
     * @param \Ignite\Interfaces\Auth\Role $role
     *
     * @return void
     */
    public function __construct(Role $role)
    {
        $this->model = $role;
    }

    /**
     * Update a particular data entry in the database.
     *
     * @param int|string|\Illuminate\Database\Eloquent\Model|\Ignite\Interfaces\Shared\Entity $entity
     * @param array                                                                           $data
     * @param array|null                                                                      $options
     *
     * @return \Illuminate\Database\Eloquent\Model|\Ignite\Interfaces\Shared\Entity
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function update(
        int|string|Model|Entity $entity,
        array $data,
        ?array $options = []
    ): Model|Entity {
        /** @var \Ignite\Interfaces\Auth\Role $role */
        $role = parent::update($entity, $data, $options);

        if (isset($data['permissions'])) {
            $role->allowTo($data['permissions']);
        }

        return $role;
    }
}
