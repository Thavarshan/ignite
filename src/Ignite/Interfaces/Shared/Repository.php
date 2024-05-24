<?php

namespace Ignite\Interfaces\Shared;

use Closure;
use Filterable\Interfaces\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface Repository
{
    /**
     * List all data entries after performing requested filters.
     *
     * @param \Filterable\Interfaces\Filter $filter
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function list(Filter $filter): Builder;

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
     * the attributes to a new model instance.
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
     * @param \Illuminate\Database\Eloquent\Model|\Ignite\Interfaces\Shared\Entity $entity
     * @param array<int, mixed>                                                    $arguments
     *
     * @return void
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function delete(
        int|string|Model|Entity $entity,
        ...$arguments
    ): void;

    /**
     * Set the create action callback.
     *
     * @param Closure $callback
     *
     * @return void
     */
    public function createUsing(Closure $callback): void;

    /**
     * Get the create action callback.
     *
     * @return Closure|null
     */
    public function getCreateUsingCallback(): ?Closure;

    /**
     * Set the update action callback.
     *
     * @param Closure $callback
     *
     * @return void
     */
    public function updateUsing(Closure $callback): void;

    /**
     * Get the update action callback.
     *
     * @return Closure|null
     */
    public function getUpdateUsingCallback(): ?Closure;

    /**
     * Set the delete action callback.
     *
     * @param Closure $callback
     *
     * @return void
     */
    public function deleteUsing(Closure $callback): void;

    /**
     * Get the delete action callback.
     *
     * @return Closure|null
     */
    public function getDeleteUsingCallback(): ?Closure;

    /**
     * Get the model instance that is associasted with the repository.
     *
     * @return \Illuminate\Database\Eloquent\Model|\Ignite\Interfaces\Shared\Entity|null
     */
    public function getModel(): Model|Entity|null;
}
