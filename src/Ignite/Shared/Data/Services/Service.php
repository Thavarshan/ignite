<?php

namespace Ignite\Shared\Data\Services;

use Ignite\Interfaces\Shared\Entity;
use Ignite\Interfaces\Shared\Service as ServiceInterface;
use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class Service implements ServiceInterface
{
    /**
     * The repository instance.
     *
     * @var \Ignite\Interfaces\Shared\Repository
     */
    protected $repository;

    /**
     * Get all of the data entries from the database.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all(): Collection
    {
        return $this->repository->all();
    }

    /**
     * Find a particular data entry in the database by given id.
     *
     * @param string|int $id
     *
     * @return \Illuminate\Database\Eloquent\Model|\Ignite\Interfaces\Shared\Entity
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function find(string|int $id): Model|Entity
    {
        return $this->repository->find($id);
    }

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
    public function findBy(string $column, mixed $value): Model|Entity
    {
        return $this->repository->findBy($column, $value);
    }

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
    ): Builder {
        return $this->repository->where(
            $column,
            $operator,
            $value,
            $boolean
        );
    }

    /**
     * Add a "where in" clause to the query.
     *
     * @param \Illuminate\Contracts\Database\Query\Expression|string $column
     * @param mixed                                                  $values
     * @param string                                                 $boolean
     * @param bool                                                   $not
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function whereIn(
        Expression|string $column,
        mixed $values,
        string $boolean = 'and',
        bool $not = false
    ): Builder {
        return $this->repository->whereIn(
            $column,
            $values,
            $boolean,
            $not
        );
    }

    /**
     * Create new data entry in the database and assign
     * given attributes to it.
     *
     * @param array<int, mixed> $data
     * @param array|null        $options
     *
     * @return \Illuminate\Database\Eloquent\Model|\Ignite\Interfaces\Shared\Entity
     */
    public function create(array $data, ?array $options = []): Model|Entity
    {
        return $this->repository->create($data, $options);
    }

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
    ): Model|Entity {
        return $this->repository->update($entity, $data, $options);
    }

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
    ): void {
        $this->repository->delete($entity, ...$arguments);
    }
}
