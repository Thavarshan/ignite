<?php

namespace Ignite\Interfaces\Shared;

use RuntimeException;

interface Cancellable
{
    /**
     * Cancel the model visibility but persist it in the database.
     *
     * This method is useful when you want to cancel a model but still keep it in the database.
     *
     * @return void
     *
     * @throws RuntimeException
     */
    public function cancel(): void;
}
