<?php

namespace Ignite\Interfaces\Shared;

interface Notifiable
{
    /**
     * Send the given notification.
     *
     * @param mixed $instance
     *
     * @return void
     */
    public function notify($instance);
}
