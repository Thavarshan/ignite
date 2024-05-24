<?php

namespace Ignite\Users\Jobs;

use App\Support\Loggable;
use Ignite\Users\Abstracts\UserAbstract;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UserJob extends UserAbstract
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use Loggable;
}
