<?php

namespace Ignite\Shared\Support;

use Illuminate\Support\Reflector as IlluminateReflector;
use ReflectionClass;

class Reflector extends IlluminateReflector
{
    /**
     * Get a reflection of the given instance.
     *
     * @param object $instance
     *
     * @return ReflectionClass
     */
    public static function instance(object $instance): ReflectionClass
    {
        return new ReflectionClass($instance);
    }
}
