<?php

namespace Ignite\Discussions\Actions;

use Ignite\Interfaces\Discussions\DeletesReplies;
use Ignite\Interfaces\Discussions\Reply;

class DeleteReply implements DeletesReplies
{
    /**
     * Delete a reply.
     *
     * @param \Ignite\Interfaces\Discussions\Reply $reply
     *
     * @return void
     */
    public function delete(Reply $reply): void
    {
        $reply->delete();
    }
}
