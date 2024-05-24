<?php

namespace Ignite\Interfaces\Shared;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface Service
{
    /**
     * Get all of the data entries from the database.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all(): Collection;

    /**
     * Find a particular data entry in the database by given id.
     *
     * @param string|int $id
     *
     * @return \Illuminate\Database\Eloquent\Model|\Ignite\Interfaces\Shared\Entity
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function find(string|int $id): Model|Entity;

    /**
     * Find a particular data entry in the database by given column and value.
     *
     * @param string $column
     * @param mixed  $value
     *
     * @return \Illuminate\Database\Eloquent\Model|\Ignite\Interfaces\Shared\Entity
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findBy(string $column, mixed $value): Model|Entity;

    /**
     * Find all resources by column and value.
     *
     * @param mixed  $column
     * @param mixed  $operator
     * @param mixed  $value
     * @param string $boolean
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function where(
        mixed $column,
        mixed $operator = null,
        mixed $value = null,
        string $boolean = 'and'
    ): Builder;

    /**
     * Create new data entry in the database and assign
     * given attributes to it.
     *
     * @param array<int, mixed> $data
     * @param array             $options
     *
     * @return \Illuminate\Database\Eloquent\Model|\Ignite\Interfaces\Shared\Entity
     */
    public function create(array $data, ?array $options = []): Model|Entity;

    /**
     * Update a particular data entry in the database.
     *
     * @param \Illuminate\Database\Eloquent\Model|\Ignite\Interfaces\Shared\Entity $entity
     * @param array<int, mixed>                                                    $data
     * @param array|null                                                           $options
     *
     * @return \Illuminate\Database\Eloquent\Model|\Ignite\Interfaces\Shared\Entity
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function update(
        int|string|Model|Entity $entity,
        array $data,
        ?array $options = []
    ): Model|Entity;

    /**
     * Delete a particular data entry in the database.
     *
     * @param array $arguments
     *
     * @return void
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function delete(
        int|string|Model|Entity $entity,
        ...$arguments
    ): void;
}
