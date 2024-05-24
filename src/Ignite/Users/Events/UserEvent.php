<?php

namespace Ignite\Users\Events;

use Ignite\Users\Abstracts\UserAbstract;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserEvent extends UserAbstract
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;
}
