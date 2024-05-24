<?php

namespace Ignite\Shared\Traits;

trait HasName
{
    /**
     * Get the name of the model.
     *
     * @return string
     */
    public function getName(): string
    {
        if (has_attribute($this, 'name') && ! is_null($this->name)) {
            return $this->name;
        }

        return '';
    }
}
