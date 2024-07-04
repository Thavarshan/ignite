<?php

namespace Ignite\Search\Engines;

use App\Support\InteractsWithContainer;
use Ignite\Interfaces\Search\Engine;
use Ignite\Interfaces\Search\EngineManager as EngineManagerInterface;
use Ignite\Interfaces\Search\Manifest;
use Ignite\Interfaces\Search\Query;
use Ignite\Interfaces\Search\Searchable as SearchableInterface;
use Ignite\Search\Traits\ScopesToUser;
use Illuminate\Contracts\Pagination\Paginator as PaginatorInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Laravel\Scout\Searchable;

class EngineManager implements EngineManagerInterface
{
    use InteractsWithContainer;
    use ScopesToUser;

    /**
     * The results of the search.
     *
     * @var array
     */
    protected $results = [];

    /**
     * The maximum number of results to return.
     *
     * @var int
     */
    protected ?int $limit = 5;

    /**
     * Create a new engine manager instance.
     *
     * @param \Ignite\Interfaces\Search\Engine   $engine
     * @param \Ignite\Interfaces\Search\Manifest $manifest
     *
     * @return void
     */
    public function __construct(
        protected Engine $engine,
        protected Manifest $manifest
    ) {
    }

    /**
     * Perform a search.
     *
     * @param \Ignite\Interfaces\Search\Query $query
     * @param bool                            $paginated
     *
     * @return \Illuminate\Support\Collection|\Illuminate\Contracts\Pagination\Paginator
     */
    public function search(
        Query $query,
        bool $paginated = false
    ): Collection|PaginatorInterface {
        $models = $this->manifest->getModels();

        collect($models)->each(function (Model|string $model) use ($query) {
            if (is_string($model)) {
                $model = $this->make($model);
            }

            if (
                ! in_array(Searchable::class, class_uses_recursive($model))
                || ! $model instanceof SearchableInterface
            ) {
                return;
            }

            $this->engine
                ->on($model)
                ->scopeToUser($this->getUser())
                ->setLimit($this->getLimit())
                ->search($query);

            $results = $this->engine->getResults();

            if ($results->isNotEmpty()) {
                $this->results[$model->getTable()] = $results;
            }
        });

        if ($paginated) {
            return $this->getPaginatedResults();
        }

        return $this->getResults();
    }

    /**
     * Get the results of the search.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getResults(): Collection
    {
        return collect($this->results);
    }

    /**
     * Get the paginated results of the search.
     *
     * @return \Illuminate\Contracts\Pagination\Paginator
     */
    public function getPaginatedResults(): PaginatorInterface
    {
        return new LengthAwarePaginator(
            $this->getResults(),
            $this->getResults()->count(),
            $this->engine->getPerPage(),
            Paginator::resolveCurrentPage(),
            ['path' => Paginator::resolveCurrentPath()]
        );
    }

    /**
     * Get the maximum number of results to return.
     *
     * @return int
     */
    public function getLimit(): int
    {
        return config('search.limit', $this->limit);
    }

    /**
     * Set the maximum number of results to return.
     *
     * @param int $limit
     *
     * @return self
     */
    public function setLimit(int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }
}
