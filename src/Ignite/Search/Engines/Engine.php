<?php

namespace Ignite\Search\Engines;

use Ignite\Search\Traits\ScopesToUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

abstract class Engine
{
    use ScopesToUser;

    /**
     * The model to perform the search on.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * The number of results per page.
     *
     * @var int
     */
    protected $perPage = 20;

    /**
     * The array of results.
     *
     * @var \Illuminate\Support\Collection|array
     */
    protected $results = [];

    /**
     * Set the model to perform the search on.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return self
     */
    public function on(Model $model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Get the number of results per page.
     *
     * @return int
     */
    public function getPerPage(): int
    {
        return $this->perPage;
    }

    /**
     * Get the array of results.
     *
     * @return \Illuminate\Support\Collection|array
     */
    public function getResults(): Collection|array
    {
        return $this->results;
    }
}
