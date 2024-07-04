<?php

namespace Ignite\Auth\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

abstract class AuthEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;
}
