<?php

namespace Ignite\Tasks\Filters;

use Ignite\Interfaces\Organisations\OrganisationService;
use Ignite\Interfaces\Organisations\TeamService;
use Ignite\Interfaces\Tasks\TaskFilter as TaskFilterInterface;
use Ignite\Interfaces\Users\UserService;
use Ignite\Shared\Data\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class TaskFilter extends Filter implements TaskFilterInterface
{
    /**
     * Attributes to filters from.
     *
     * @var array
     */
    protected $filters = [
        'name',
        'slug',
        'organisation',
        'taskable',
        'status',
        'users',
        'teams',
    ];

    /**
     * Create a new filter instance.
     *
     * @param \Illuminate\Http\Request                             $request
     * @param \Ignite\Interfaces\Organisations\OrganisationService $organisations
     * @param \Ignite\Interfaces\Users\UserService                 $users
     * @param \Ignite\Interfaces\Organisations\TeamService         $teams
     *
     * @return void
     */
    public function __construct(
        Request $request,
        protected readonly OrganisationService $organisations,
        protected readonly UserService $users,
        protected readonly TeamService $teams
    ) {
        parent::__construct($request);
    }

    /**
     * Filter tasks by the given name.
     *
     * @param string $name
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function name(string $name): Builder
    {
        return $this->builder->where('name', 'like', "%{$name}%");
    }

    /**
     * Filter tasks by the given slug.
     *
     * @param string $slug
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function slug(string $slug): Builder
    {
        return $this->builder->where('slug', $slug);
    }

    /**
     * Filter tasks by the given organisation slug.
     *
     * @param string $slug
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function organisation(string $slug): Builder
    {
        return $this->builder->whereHas(
            'organisation',
            function ($query) use ($slug) {
                $query->where('slug', $slug);
            }
        );
    }

    /**
     * Filter tasks by the given taskable type.
     *
     * @param string $type
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function taskable(string $type): Builder
    {
        return $this->builder->where('taskable_type', $type);
    }

    /**
     * Filter tasks by the given status.
     *
     * @param string $status
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function status(string $status): Builder
    {
        // return $this->builder->where('status', $status);
    }

    /**
     * Filter tasks by the given user email.
     *
     * @param string $email
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function users(string $email): Builder
    {
        return $this->builder->whereHas(
            'users',
            function ($query) use ($email) {
                $query->where('email', $email);
            }
        );
    }

    /**
     * Filter tasks by the given team slug.
     *
     * @param string $slug
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function teams(string $slug): Builder
    {
        return $this->builder->whereHas(
            'teams',
            function ($query) use ($slug) {
                $query->where('slug', $slug);
            }
        );
    }
}
