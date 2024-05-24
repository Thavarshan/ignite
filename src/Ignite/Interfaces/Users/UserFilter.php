<?php

namespace Ignite\Interfaces\Users;

use Ignite\Interfaces\Shared\Filter;
use Illuminate\Database\Eloquent\Builder;

interface UserFilter extends Filter
{
    /**
     * Filter users by given name.
     *
     * @param string $name
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function name(string $name): Builder;

    /**
     * Filter users by given email.
     *
     * @param string $email
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function email(string $email): Builder;

    /**
     * Filter users by given organisation.
     *
     * @param string $slug
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function organisation(string $slug): Builder;

    /**
     * Filter users by given role.
     *
     * @param string $slug
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function role(string $slug): Builder;

    /**
     * Filter the query by a given domain.
     *
     * @param string $domain
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function domain(string $domain): Builder;

    /**
     * Filter the query by a given created at date.
     *
     * @param string $date
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function createdAt(string $date): Builder;
}
