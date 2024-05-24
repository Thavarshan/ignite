<?php

namespace Ignite\Interfaces\Shared;

use ArrayAccess;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use JsonSerializable;
use LogicException;

/**
 * @property int                             $id
 * @property string|null                     $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
interface Entity extends Arrayable, ArrayAccess, Jsonable, JsonSerializable
{
    /**
     * Get the primary key for the model.
     *
     * @return string
     */
    public function getKeyName();

    /**
     * Get the value of the model's primary key.
     *
     * @return int|string
     */
    public function getId(): int|string;

    /**
     * Get all of the models from the database.
     *
     * @param array|string $columns
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll(array|string $columns = ['*']): Collection;

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
    public function findBy(string $column, mixed $value): Model|Entity;

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
    public function findOrFail(mixed $id, array|string $columns = ['*']): Model|Entity;

    /**
     * Execute the query and get the first result or throw an exception.
     *
     * @param array|string $columns
     *
     * @return \Illuminate\Database\Eloquent\Model|\Ignite\Interfaces\Shared\Entity
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException<\Illuminate\Database\Eloquent\Model>
     */
    public function firstOrFail(array|string $columns = ['*']): Model|Entity;

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
    ): Builder;

    /**
     * Create a new instance of the given model.
     *
     * @param array $attributes
     * @param bool  $exists
     *
     * @return static
     */
    public function newInstance($attributes = [], $exists = false);

    /**
     * Get an attribute from the model.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function getAttribute($key);

    /**
     * Fill the model with an array of attributes.
     *
     * @param array $attributes
     *
     * @return self
     *
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    public function fill(array $attributes);

    /**
     * Fill the model with an array of attributes. Force mass assignment.
     *
     * @param array $attributes
     *
     * @return self
     */
    public function forceFill(array $attributes);

    /**
     * Save the model to the database.
     *
     * @param array $options
     *
     * @return bool
     */
    public function save(array $options = []);

    /**
     * Create a new instance of the model.
     *
     * @param array $attributes
     * @param array $options
     *
     * @return \Illuminate\Database\Eloquent\Model|\Ignite\Interfaces\Shared\Entity
     */
    public function create(array $attributes, ?array $options = []): Model|Entity;

    /**
     * Reload a fresh model instance from the database.
     *
     * @param array|string $with
     *
     * @return static|null
     */
    public function fresh($with = []);

    /**
     * Reload the current model instance with fresh attributes from the database.
     *
     * @return self
     */
    public function refresh();

    /**
     * Update the model in the database.
     *
     * @param array $attributes
     * @param array $options
     *
     * @return bool
     */
    public function update(array $attributes = [], array $options = []);

    /**
     * Delete the model from the database.
     *
     * @return bool|null
     *
     * @throws LogicException
     */
    public function delete();

    /**
     * Get the fillable attributes for the model.
     *
     * @return array<string>
     */
    public function getFillable();

    /**
     * Execute the query as a "select" statement.
     *
     * @param array|string $columns
     *
     * @return \Illuminate\Database\Eloquent\Collection|array<static>
     */
    public function get($columns = ['*']);

    /**
     * Begin querying the model.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getQuery(): Builder;

    /**
     * Eager load relations on the model.
     *
     * @param array|string $relations
     *
     * @return self
     */
    public function load($relations);

    /**
     * Determine if the model is the related instance of the relationship.
     *
     * @param \Illuminate\Database\Eloquent\Model|null $model
     *
     * @return bool
     */
    public function is($model);

    /**
     * Get a subset of the model's attributes.
     *
     * @param array|mixed $attributes
     *
     * @return array
     */
    public function only($attributes);
}
