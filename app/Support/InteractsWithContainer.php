<?php

namespace App\Support;

use Closure;
use Illuminate\Contracts\Pipeline\Pipeline as PipelineContract;
use InvalidArgumentException;

trait InteractsWithContainer
{
    /**
     * Get the available container instance.
     *
     * @param string|null $abstract
     * @param array       $parameters
     *
     * @return mixed|\Illuminate\Contracts\Foundation\Application
     */
    public function app(
        ?string $abstract = null,
        array $parameters = []
    ): mixed {
        return $this->resolve($abstract, $parameters);
    }

    /**
     * Resolve the given type from the container.
     *
     * @param Closure|string $abstract
     * @param array          $parameters
     *
     * @return mixed
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \Illuminate\Contracts\Container\CircularDependencyException
     */
    public function resolve(
        Closure|string|null $abstract = null,
        array $parameters = []
    ) {
        return resolve($abstract, $parameters);
    }

    /**
     * Resolve the given type from the container.
     *
     * @param Closure|string $abstract
     * @param array          $parameters
     *
     * @return mixed
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function make(
        Closure|string|null $abstract = null,
        array $parameters = []
    ) {
        return $this->resolve($abstract, $parameters);
    }

    /**
     * An alias function name for make().
     *
     * @param Closure|string $abstract
     * @param array          $parameters
     *
     * @return mixed
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function makeWith(
        Closure|string $abstract,
        array $parameters = []
    ): mixed {
        return $this->make($abstract, $parameters);
    }

    /**
     * Call the given Closure / class@method and inject its dependencies.
     *
     * @param Closure|string $callback
     * @param array          $parameters
     * @param string|null    $defaultMethod
     *
     * @return mixed
     *
     * @throws InvalidArgumentException
     */
    public function call(
        Closure|string $callback,
        array $parameters = [],
        ?string $defaultMethod = null
    ): mixed {
        return $this->resolve()->call(
            $this,
            $callback,
            $parameters,
            $defaultMethod
        );
    }

    /**
     * Create new instance of pipeline handler.
     *
     * @return \Illuminate\Contracts\Pipeline\Pipeline
     */
    public function pipeline(): PipelineContract
    {
        return $this->resolve('pipeline');
    }
}
