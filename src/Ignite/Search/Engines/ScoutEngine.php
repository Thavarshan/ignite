<?php

namespace Ignite\Search\Engines;

use Ignite\Interfaces\Search\Engine as EngineInterface;
use Ignite\Interfaces\Search\Query;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Laravel\Scout\Builder as ScoutBuilder;

class ScoutEngine extends Engine implements EngineInterface
{
    /**
     * The limit of results to return.
     *
     * @var int|null
     */
    protected ?int $limit = 5;

    /**
     * Search the engine.
     *
     * @param \Ignite\Interfaces\Search\Query $query
     *
     * @return \Illuminate\Support\Collection
     */
    public function search(Query $query): Collection
    {
        // Refresh results and not merge previous search results with new ones.
        $this->results = collect([]);

        collect($query->getQuery())->each(function ($value, $key) {
            $this->results = $this->results->merge(
                $this->determineSearchType($key)
                    ? $this->performNativeSearch($key, $value)
                    : $this->performScoutSearch($key, $value)
            );
        });

        $this->filterResults();

        return $this->getResults();
    }

    /**
     * Get the limit of results to return.
     *
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * Set the limit of results to return.
     *
     * @param int $limit
     *
     * @return self
     */
    public function setLimit(int $limit)
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * Determine the search type.
     *
     * @param string $key
     *
     * @return bool
     */
    protected function determineSearchType(string $key): bool
    {
        return ! is_numeric($key)
            && ($key !== 'q' && has_attribute($this->model, $key));
    }

    /**
     * Perform a native model search.
     *
     * @param string $key
     * @param string $value
     *
     * @return \Illuminate\Support\Collection
     */
    protected function performNativeSearch(
        string $key,
        string $value
    ): Collection {
        /** @var \Illuminate\Database\Eloquent\Builder $query */
        $query = $this->model->where($key, 'LIKE', "%{$value}%");

        $query = $this->performScoping($query);

        if (! is_null($this->limit) && $this->limit > 0) {
            $query->take($this->limit);
        }

        return $query->get();
    }

    /**
     * Perform a scout search.
     *
     * @param string $key
     * @param string $value
     *
     * @return \Illuminate\Support\Collection
     *
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter
     */
    protected function performScoutSearch(
        string $key,
        string $value
    ): Collection {
        $query = $this->model::search($value); // @phpstan-ignore-line

        $query = $this->performScoping($query);

        if (! is_null($this->limit) && $this->limit > 0) {
            $query->take($this->limit);
        }

        return $query->get();
    }

    /**
     * Perform scoping on the query based on the user.
     *
     * @param \Illuminate\Database\Eloquent\Builder|\Laravel\Scout\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Laravel\Scout\Builder
     */
    protected function performScoping(Builder|ScoutBuilder $query): Builder|ScoutBuilder
    {
        if (is_null($this->getUser())) {
            return $query;
        }

        if (has_attribute($this->model, 'organisation_id')) {
            $query->where(
                'organisation_id',
                $this->getUser()->getOrganisationId()
            );
        }

        if (has_attribute($this->model, 'team_id')) {
            $query->where(
                'team_id',
                $this->getUser()->getCurrentTeamId()
            );
        }

        return $query;
    }

    /**
     * Filter results to remove duplicate occurrences.
     *
     * @return void
     */
    protected function filterResults(): void
    {
        $ids = $this->results->pluck('id')->toArray();

        $this->results = $this->results->filter(function ($result) use (&$ids) {
            $id = $result->getAttribute('id');

            if (in_array($id, $ids)) {
                $ids = array_diff($ids, [$id]);

                return true;
            }

            return false;
        });
    }
}
