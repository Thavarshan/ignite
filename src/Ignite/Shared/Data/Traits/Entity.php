<?php

namespace Ignite\Shared\Data\Traits;

use Ignite\Interfaces\Shared\Entity as EntityInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

trait Entity
{
    /**
     * Get the value of the model's primary key.
     *
     * @return int|string
     */
    public function getId(): int|string
    {
        return $this->getKey();
    }

    /**
     * Get all of the models from the database.
     *
     * @param array|string $columns
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll($columns = ['*']): Collection
    {
        return static::all($columns);
    }

    /**
     * Find a specific resource by column and value.
     *
     * @param string $column
     * @param mixed  $value
     *
     * @return \Illuminate\Database\Eloquent\Model|\Ignite\Interfaces\Shared\Entity
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findBy(
        string $column,
        mixed $value
    ): Model|EntityInterface {
        return static::query()->where($column, $value)->firstOrFail();
    }

    /**
     * Find a model by its primary key or throw an exception.
     *
     * @param mixed        $id
     * @param array|string $columns
     *
     * @return \Illuminate\Database\Eloquent\Model|\Ignite\Interfaces\Shared\Entity
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException<\Illuminate\Database\Eloquent\Model>
     */
    public function findOrFail(
        mixed $id,
        array|string $columns = ['*']
    ): Model|EntityInterface {
        return static::query()->findOrFail($id, $columns);
    }

    /**
     * Execute the query and get the first result or throw an exception.
     *
     * @param array|string $columns
     *
     * @return \Illuminate\Database\Eloquent\Model|\Ignite\Interfaces\Shared\Entity
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException<\Illuminate\Database\Eloquent\Model>
     */
    public function firstOrFail(
        array|string $columns = ['*']
    ): Model|EntityInterface {
        return static::query()->firstOrFail($columns);
    }

    /**
     * Add a basic where clause to the query.
     *
     * @param mixed  $column
     * @param mixed  $operator
     * @param mixed  $value
     * @param string $boolean
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function queryWhere(
        mixed $column,
        mixed $operator = null,
        mixed $value = null,
        string $boolean = 'and'
    ): Builder {
        return static::query()->where($column, $operator, $value, $boolean);
    }

    /**
     * Create a new instance of the model.
     *
     * @param array $attributes
     * @param array $options
     *
     * @return \Illuminate\Database\Eloquent\Model|\Ignite\Interfaces\Shared\Entity
     */
    public function create(
        array $attributes,
        ?array $options = []
    ): Model|EntityInterface {
        return static::query()->create($attributes, $options);
    }

    /**
     * Execute the query as a "select" statement.
     *
     * @param array|string $columns
     *
     * @return \Illuminate\Database\Eloquent\Collection|array<static>
     */
    public function get($columns = ['*'])
    {
        return static::query()->get($columns);
    }

    /**
     * Begin querying the model.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getQuery(): Builder
    {
        return static::query();
    }
}
