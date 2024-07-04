<?php

namespace Ignite\Discussions\Actions;

use Ignite\Discussions\Support\Body;
use Ignite\Interfaces\Discussions\Reply;
use Ignite\Interfaces\Discussions\UpdatesReplies;

class UpdateReply implements UpdatesReplies
{
    /**
     * Update the reply.
     *
     * @param \Ignite\Interfaces\Discussions\Reply $reply
     * @param string                               $body
     *
     * @return \Ignite\Interfaces\Discussions\Reply
     */
    public function update(Reply $reply, string $body): Reply
    {
        $reply->update(['body' => new Body($body)]);

        return $reply->fresh();
    }
}
