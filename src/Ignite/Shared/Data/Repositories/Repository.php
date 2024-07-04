<?php

namespace Ignite\Shared\Data\Repositories;

use Closure;
use Ignite\Interfaces\Shared\Entity;
use Ignite\Interfaces\Shared\Filter;
use Ignite\Interfaces\Shared\Filterable;
use Ignite\Interfaces\Shared\Repository as RepositoryInterface;
use Ignite\Shared\Data\Traits\Fillable;
use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

abstract class Repository implements RepositoryInterface
{
    use Fillable;

    /**
     * The eloquent model instance.
     *
     * @var \Illuminate\Database\Eloquent\Model|\Ignite\Interfaces\Shared\Entity|null
     */
    protected $model;

    /**
     * The create action callback.
     *
     * @var Closure|null
     */
    protected $createUsingCallback;

    /**
     * The update action callback.
     *
     * @var Closure|null
     */
    protected $updateUsingCallback;

    /**
     * The delete action callback.
     *
     * @var Closure|null
     */
    protected $deleteUsingCallback;

    /**
     * List all data entries after performing requested filters.
     *
     * @param \Ignite\Interfaces\Shared\Filter|null $filter
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function list(?Filter $filter = null): Builder
    {
        return transform($this->getModel(), function (
            Model|Entity|Filterable $entity
        ) use ($filter) {
            if (! is_null($filter) && $entity instanceof Filterable) {
                return $entity->filter($filter);
            }

            return $entity->newQuery();
        });
    }

    /**
     * Get all of the data entries from the database.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all(): Collection
    {
        return $this->getModel()->get();
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
        $model = $this->getModel();

        $keyName = $model->getKeyName();
        $routeKeyName = $model->getRouteKeyName();

        $result = $model->where(function (Builder $query) use ($id, $keyName, $routeKeyName) {
            $query->where($keyName, $id)->orWhere($routeKeyName, $id);
        })->first();

        if (! $result) {
            throw new ModelNotFoundException(
                "No query results for model [{$model->getMorphClass()}] {$id}"
            );
        }

        return $result;
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
        return $this->getModel()?->where($column, $value)->firstOrFail();
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
        return $this->getModel()?->queryWhere(
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
        return $this->getModel()?->queryWhereIn(
            $column,
            $values,
            $boolean,
            $not
        );
    }

    /**
     * Create new data entry in the database and assign
     * the attributes to a new model instance.
     *
     * @param array      $data
     * @param array|null $options
     *
     * @return \Illuminate\Database\Eloquent\Model|\Ignite\Interfaces\Shared\Entity
     */
    public function create(array $data, ?array $options = []): Model|Entity
    {
        $callback = $this->getCreateUsingCallback()
            ?? function () use ($data, $options) {
                if (isset($options['if_exists'])) {
                    return $this->getModel()?->firstOrCreate($data, $data);
                }

                return $this->getModel()?->create($data);
            };

        return DB::transaction(fn () => call_user_func($callback, ...[
            $this->filterFillable($data, $this->getModel()),
            $options,
        ]));
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
        $callback = $this->getUpdateUsingCallback()
            ?? function () use ($entity, $data, $options) {
                return tap(! ($entity instanceof Model) || ! ($entity instanceof Entity) // phpcs:ignore
                    ? $this->findBy($this->model->getKeyName(), $entity)
                    : $entity, function (Model|Entity $entity) use ($data, $options) { // phpcs:ignore
                        $entity->update(
                            $this->filterFillable($data, $this->getModel()),
                            $options,
                        );
                    });
            };

        return DB::transaction(fn () => call_user_func($callback, [
            $entity,
            $this->filterFillable($data, $this->getModel()),
            $options,
        ]));
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
        $callback = $this->getDeleteUsingCallback()
            ?? function () use ($entity) {
                return tap(! ($entity instanceof Model) || ! ($entity instanceof Entity) // phpcs:ignore
                    ? $this->findBy($this->model->getKeyName(), $entity)
                    : $entity, function (Model|Entity $entity) {
                        $entity->delete();
                    });
            };

        DB::transaction(fn () => call_user_func(
            $callback,
            [$entity, ...$arguments]
        ));
    }

    /**
     * Set the create action callback.
     *
     * @param Closure $callback
     *
     * @return void
     */
    public function createUsing(Closure $callback): void
    {
        $this->createUsingCallback = $callback;
    }

    /**
     * Get the create action callback.
     *
     * @return Closure|null
     */
    public function getCreateUsingCallback(): ?Closure
    {
        return $this->createUsingCallback;
    }

    /**
     * Set the update action callback.
     *
     * @param Closure $callback
     *
     * @return void
     */
    public function updateUsing(Closure $callback): void
    {
        $this->updateUsingCallback = $callback;
    }

    /**
     * Get the update action callback.
     *
     * @return Closure|null
     */
    public function getUpdateUsingCallback(): ?Closure
    {
        return $this->updateUsingCallback;
    }

    /**
     * Set the delete action callback.
     *
     * @param Closure $callback
     *
     * @return void
     */
    public function deleteUsing(Closure $callback): void
    {
        $this->deleteUsingCallback = $callback;
    }

    /**
     * Get the delete action callback.
     *
     * @return Closure|null
     */
    public function getDeleteUsingCallback(): ?Closure
    {
        return $this->deleteUsingCallback;
    }

    /**
     * Get the model instance that is associasted with the repository.
     *
     * @return \Illuminate\Database\Eloquent\Model|\Ignite\Interfaces\Shared\Entity|null
     */
    public function getModel(): Model|Entity|null
    {
        return $this->model;
    }
}
