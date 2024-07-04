<?php

namespace Ignite\Users\Filters;

use Ignite\Interfaces\Auth\RoleService;
use Ignite\Interfaces\Organisations\OrganisationService;
use Ignite\Interfaces\Users\UserFilter as UserFilterInterface;
use Ignite\Shared\Data\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class UserFilter extends Filter implements UserFilterInterface
{
    /**
     * Registered filters to operate upon.
     *
     * @var array<string>
     */
    protected $filters = [
        'name',
        'email',
        'domain',
        'organisation',
        'role',
        'created_at',
    ];

    /**
     * Create a new filter instance.
     *
     * @param \Illuminate\Http\Request                             $request
     * @param \Ignite\Interfaces\Organisations\OrganisationService $organisations
     * @param \Ignite\Interfaces\Auth\RoleService                  $roles
     *
     * @return void
     */
    public function __construct(
        Request $request,
        protected OrganisationService $organisations,
        protected RoleService $roles
    ) {
        parent::__construct($request);
    }

    /**
     * Filter users by given name.
     *
     * @param string $name
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function name(string $name): Builder
    {
        return $this->builder->where(function (Builder $query) use ($name): void {
            $query->where('firstname', 'like', "%{$name}%")
                ->orWhere('lastname', 'like', "%{$name}%");
        });
    }

    /**
     * Filter users by given email.
     *
     * @param string $email
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function email(string $email): Builder
    {
        return $this->builder->where('email', 'like', "%{$email}%");
    }

    /**
     * Filter users by given organisation.
     *
     * @param string $slug
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function organisation(string $slug): Builder
    {
        return $this->builder->whereBelongsTo(
            $this->organisations->findBy('slug', $slug)
        );
    }

    /**
     * Filter users by given role.
     *
     * @param string $slug
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function role(string $slug): Builder
    {
        return $this->builder->whereBelongsTo(
            $this->roles->findBy('slug', $slug)
        );
    }

    /**
     * Filter the query by a given domain.
     *
     * @param string $domain
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function domain(string $domain): Builder
    {
        return $this->builder->where('email', 'like', "%@{$domain}%");
    }

    /**
     * Filter the query by a given created at date.
     *
     * @param string $date
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function createdAt(string $date): Builder
    {
        return $this->builder->whereDate('created_at', $date);
    }
}
