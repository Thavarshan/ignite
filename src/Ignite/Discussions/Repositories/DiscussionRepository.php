<?php

namespace Ignite\Discussions\Repositories;

use Ignite\Interfaces\Discussions\Discussion;
use Ignite\Interfaces\Discussions\DiscussionRepository as DiscussionRepositoryInterface;
use Ignite\Shared\Data\Repositories\Repository;

class DiscussionRepository extends Repository implements DiscussionRepositoryInterface
{
    /**
     * Create a new repository instance.
     *
     * @param \Ignite\Interfaces\Discussions\Discussion $discussion
     *
     * @return void
     */
    public function __construct(Discussion $discussion)
    {
        $this->model = $discussion;
    }
}
