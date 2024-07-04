<?php

namespace Ignite\Shared\Data\Filters;

use Closure;
use Ignite\Auth\Facades\Auth;
use Ignite\Interfaces\Organisations\Organisation;
use Ignite\Interfaces\Shared\Filter as FilterInterface;
use Ignite\Interfaces\Users\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

abstract class Filter implements FilterInterface
{
    /**
     * The Eloquent builder.
     *
     * @var \Illuminate\Database\Eloquent\Builder
     */
    protected $builder;

    /**
     * The pre-filters to apply to the query.
     *
     * @var Closure|null
     */
    protected $preFilters;

    /**
     * The organisation ID to filter by.
     *
     * @var int|null
     */
    protected $forOrganisation;

    /**
     * The authenticated user.
     *
     * @var \Ignite\Interfaces\Users\User|null
     */
    protected $user;

    /**
     * Registered filters to operate upon.
     *
     * @var array<string>
     */
    protected $filters = [];

    /**
     * All filters that have been chosed to be applied.
     *
     * @var array<string, mixed>
     */
    protected $filterables = [];

    /**
     * The current filters being applied.
     *
     * @var array<string>
     */
    protected $currentFilters = [];

    /**
     * Create a new filter instance.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return void
     */
    public function __construct(protected Request $request)
    {
    }

    /**
     * Apply the filters.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Builder $builder): Builder
    {
        $this->builder = $builder;

        if (! is_null($this->forOrganisation)) {
            $this->builder->where('organisation_id', $this->forOrganisation);
        }

        $this->applyPreFilters();

        foreach ($this->getFilters() as $filter => $value) {
            $filter = Str::camel($filter);

            if (method_exists($this, $filter)) {
                $this->$filter($value);
            }
        }

        return $this->builder;
    }

    /**
     * Get the registered filters.
     *
     * @return array<string>
     */
    public function getFilterables(): array
    {
        return $this->filters;
    }

    /**
     * Fetch all relevant filters from the request.
     *
     * @return array<string, mixed>
     */
    public function getFilters(): array
    {
        $this->filterables = array_merge(
            $this->filterables,
            array_filter($this->request->only($this->filters))
        );

        $this->currentFilters = array_keys($this->filterables);

        return $this->filterables;
    }

    /**
     * Append a filterable value to the filter.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return self
     */
    public function appendFilterable(string $key, mixed $value): self
    {
        $this->filterables[$key] = $value;

        return $this;
    }

    /**
     * Filter the query to only include records for the authenticated user.
     *
     * @param \Ignite\Interfaces\Users\User|null $user
     *
     * @return self
     */
    public function forUser(?User $user): self
    {
        $this->user = $user ?? Auth::user();

        $this->forOrganisation(
            $this->user->getOrganisation()
        );

        return $this;
    }

    /**
     * Filter the query to only include records for the given organisation.
     *
     * @param \Ignite\Interfaces\Organisations\Organisation|int $organisation
     *
     * @return self
     */
    public function forOrganisation(Organisation|int $organisation): self
    {
        $this->forOrganisation = is_int($organisation)
            ? $organisation
            : $organisation->getId();

        return $this;
    }

    /**
     * Get the current filters being applied.
     *
     * @return array<string>
     */
    public function getCurrentFilters(): array
    {
        return $this->currentFilters;
    }

    /**
     * Register pre-filters to apply to the query.
     *
     * @param Closure $callback
     *
     * @return self
     */
    public function registerPreFilters(Closure $callback): self
    {
        $this->preFilters = $callback;

        return $this;
    }

    /**
     * Apply pre-filters to the query.
     *
     * @return void
     */
    protected function applyPreFilters(): void
    {
        if (! is_null($this->preFilters)) {
            call_user_func_array($this->preFilters, [$this->builder]);
        }
    }
}
