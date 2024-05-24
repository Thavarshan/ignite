<?php

namespace Ignite\Interfaces\Shared;

interface HasName
{
    /**
     * Get the name of the model.
     *
     * @return string
     */
    public function getName(): string;
}
