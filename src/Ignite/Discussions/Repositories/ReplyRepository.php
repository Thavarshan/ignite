<?php

namespace Ignite\Discussions\Repositories;

use Ignite\Interfaces\Discussions\Reply;
use Ignite\Interfaces\Discussions\ReplyRepository as ReplyRepositoryInterface;
use Ignite\Shared\Data\Repositories\Repository;

class ReplyRepository extends Repository implements ReplyRepositoryInterface
{
    /**
     * Create a new repository instance.
     *
     * @param \Ignite\Interfaces\Discussions\Reply $reply
     *
     * @return void
     */
    public function __construct(Reply $reply)
    {
        $this->model = $reply;
    }
}
