<?php

namespace Ignite\Interfaces\Shared;

interface Parser
{
    /**
     * Parse given data.
     *
     * @param mixed $data
     *
     * @return mixed
     */
    public function parse(mixed $data): mixed;
}
