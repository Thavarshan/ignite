<?php

namespace Ignite\Shared\Data\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use RuntimeException;

trait Cancellable
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
    public function cancel(): void
    {
        if (has_attribute($this, 'cancelled_at')) {
            $this->update(['cancelled_at' => Carbon::now()]);

            return;
        }

        if (! in_array(SoftDeletes::class, class_uses_recursive($this))) {
            throw new RuntimeException('The model must use the SoftDeletes trait to be cancellable.'); // phpcs:ignore
        }

        $this->delete();
    }
}
