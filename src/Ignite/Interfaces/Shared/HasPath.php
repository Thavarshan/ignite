<?php

namespace Ignite\Interfaces\Shared;

interface HasPath
{
    /**
     * Get the full URL path to the entity.
     *
     * @return string
     */
    public function getPath(): string;
}
