<?php

namespace Ignite\Statuses\Models\Traits;

use Ignite\Statuses\Enums\Status as StatusEnum;
use Ignite\Statuses\Events\StatusUpdated;
use Ignite\Statuses\Exceptions\InvalidStatus;
use Ignite\Statuses\Models\Status;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use InvalidArgumentException;

trait HasStatuses
{
    /**
     * {@inheritdoc}
     */
    public function __get($key): mixed
    {
        if ($key === $this->getStatusAttributeName()) {
            return (string) $this->latestStatus();
        }

        return parent::__get($key);
    }
    /**
     * Get all of the model's statuses.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function statuses(): MorphMany
    {
        return $this->morphMany(
            $this->getStatusModelClassName(),
            'statusable',
            'statusable_type',
            $this->getModelKeyColumnName()
        )->latest('id');
    }

    /**
     * Get the current status of the model.
     *
     * @return \Ignite\Statuses\Models\Status|null
     */
    public function status(): ?Status
    {
        return $this->latestStatus();
    }

    /**
     * Set the status of the model.
     *
     * @param string      $name
     * @param string|null $reason
     *
     * @return self
     *
     * @throws \Ignite\Statuses\Exceptions\InvalidStatus
     */
    public function setStatus(string $name, ?string $reason = null): self
    {
        if (! $this->isValidStatus($name)) {
            throw InvalidStatus::create($name);
        }

        return $this->forceSetStatus($name, $reason);
    }

    /**
     * Check if the status is valid for the model.
     *
     * @param string $name
     *
     * @return bool
     */
    public function isValidStatus(string $name): bool
    {
        try {
            StatusEnum::isValid($name);

            return true;
        } catch (InvalidArgumentException $e) {
            return false;
        }
    }

    /**
     * Get the latest status of the model.
     *
     * @param string|array $names
     *
     * @return \Ignite\Statuses\Models\Status|null
     */
    public function latestStatus(string|array ...$names): ?Status
    {
        $statuses = $this->relationLoaded('statuses')
            ? $this->statuses
            : $this->statuses();

        $names = is_array($names) ? Arr::flatten($names) : func_get_args();

        if (count($names) < 1) {
            return $statuses->first();
        }

        return $statuses->whereIn('name', $names)->first();
    }

    /**
     * Check if the model has ever had a status with the given name.
     *
     * This method determines whether the current model instance has ever had a status
     * with the specified name.
     *
     * @param string $name The name of the status to check for.
     *
     * @return bool Returns true if the model has ever had the status with the given name, otherwise returns false.
     */
    public function hasEverHadStatus(string $name): bool
    {
        $statuses = $this->relationLoaded('statuses')
            ? $this->statuses
            : $this->statuses();

        return $statuses->where('name', $name)->count() > 0;
    }

    /**
     * Check if the model has never had a status with the given name.
     *
     * This method determines whether the current model instance has never had a status
     * with the specified name by negating the result of hasEverHadStatus.
     *
     * @param string $name The name of the status to check for.
     *
     * @return bool Returns true if the model has never had the status with the given name, otherwise returns false.
     */
    public function hasNeverHadStatus(string $name): bool
    {
        return ! $this->hasEverHadStatus($name);
    }

    /**
     * Delete the status of the model.
     *
     * @param string|array $names
     *
     * @return void
     */
    public function deleteStatus(string|array ...$names)
    {
        $names = is_array($names) ? Arr::flatten($names) : func_get_args();
        if (count($names) < 1) {
            return $this;
        }

        $this->statuses()->whereIn('name', $names)->delete();
    }

    /**
     * Scope a query to only include models that have the specified status.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param string|array                          $names
     *
     * @return void
     */
    public function scopeCurrentStatus(
        Builder $builder,
        string|array ...$names
    ): void {
        $names = is_array($names) ? Arr::flatten($names) : func_get_args();

        $builder->whereHas('statuses', function (Builder $query) use ($names) {
            $query->whereIn('name', $names)
                ->where('id', function (QueryBuilder $subQuery) {
                    $subQuery->selectRaw('max(id)')
                        ->from($this->getStatusTableName())
                        ->where('statusable_type', $this->getStatusModelType())
                        ->whereColumn(
                            $this->getModelKeyColumnName(),
                            $this->getQualifiedKeyName()
                        );
                });
        });
    }

    /**
     * Scope a query to only include models that have never had the specified status.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param string|array                          $names
     *
     * @return void
     */
    public function scopeOtherCurrentStatus(
        Builder $builder,
        string|array ...$names
    ): void {
        $names = is_array($names) ? Arr::flatten($names) : func_get_args();

        $builder->whereHas('statuses', function (Builder $query) use ($names) {
            $query->whereNotIn('name', $names)
                ->where('id', function (QueryBuilder $subQuery) {
                    $subQuery->selectRaw('max(id)')
                        ->from($this->getStatusTableName())
                        ->where('statusable_type', $this->getStatusModelType())
                        ->whereColumn(
                            $this->getModelKeyColumnName(),
                            $this->getQualifiedKeyName()
                        );
                });
        })->orWhereDoesntHave('statuses');
    }

    /**
     * Force set the status of the model.
     *
     * This method is similar to the setStatus method, but it does not check if the status is valid.
     * This method is useful when you want to set a status that is not valid in certain conditions.
     * For example, you may want to set a status that is not valid when the model is in a certain state.
     * In such cases, you can use this method to force set the status.
     *
     * @param string      $name
     * @param string|null $reason
     *
     * @return self
     */
    public function forceSetStatus(string $name, ?string $reason = null): self
    {
        $oldStatus = $this->latestStatus();

        $newStatus = $this->statuses()->create([
            'name' => $name,
            'reason' => $reason,
        ]);

        event(new StatusUpdated($oldStatus, $newStatus, $this));

        return $this;
    }

    /**
     * Get all available status names for the model.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getStatusNames(): Collection
    {
        $statusModel = app($this->getStatusModelClassName());

        return $statusModel->pluck('name');
    }

    /**
     * Check if the model has a status with the given name.
     *
     * @param string $name
     *
     * @return bool
     */
    public function hasStatus(string $name): bool
    {
        return $this->statuses()->where('name', $name)->exists();
    }

    /**
     * Get the table name of the status model.
     *
     * @return string
     */
    protected function getStatusTableName(): string
    {
        $modelClass = $this->getStatusModelClassName();

        return (new $modelClass())->getTable();
    }

    /**
     * Get the model key column name.
     *
     * @return string
     */
    protected function getModelKeyColumnName(): string
    {
        return 'statusable_id';
    }

    /**
     * Get the the class name of the status model.
     *
     * @return string
     */
    protected function getStatusModelClassName(): string
    {
        return Status::class;
    }

    /**
     * Get the name of the attribute that holds the status.
     *
     * @return string
     */
    protected function getStatusAttributeName(): string
    {
        return 'status';
    }

    /**
     * Get the class name of the status model.
     *
     * @return string
     */
    protected function getStatusModelType(): string
    {
        return array_search(
            static::class,
            Relation::morphMap()
        ) ?: static::class;
    }
}
