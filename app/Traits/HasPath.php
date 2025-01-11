<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Route as RouteFacade;
use Illuminate\Support\Str;

trait HasPath
{
    /**
     * Boot the HasPath trait for a model.
     */
    protected function initializeHasPath(): void
    {
        $this->append('path');
    }

    /**
     * Get the full path to the model.
     */
    public function path(): Attribute
    {
        return Attribute::make(fn () => $this->getPath());
    }

    /**
     * Generate the full path URL to the model.
     */
    public function getPath(): string
    {
        $modelName = Str::of(class_basename($this))->plural()->slug();
        $routes = RouteFacade::getRoutes();

        foreach ($routes as $route) {
            if (Str::contains($route->getName(), $modelName.'.show')) {
                $name = $route->getName();

                return route($name, $this->getRouteParameters($route));
            }
        }

        return '#';
    }

    /**
     * Get the route parameters for the model.
     *
     * @return array<string, string|int>
     */
    protected function getRouteParameters(Route $route): array
    {
        $parameters = [];

        foreach ($route->parameterNames() as $parameter) {
            if (isset($this->{$parameter})) {
                $parameters[$parameter] = $this->{$parameter};

                continue;
            }

            $relatedModel = $this->getRelatedModel($parameter);

            if ($relatedModel) {
                $parameters[$parameter] = $relatedModel->getRouteKey();

                continue;
            }

            $parameters[$parameter] = $this->getRouteKey();
        }

        if (method_exists($this, 'getAdditionalRouteParameters')) {
            $parameters = array_merge($parameters, $this->getAdditionalRouteParameters());
        }

        return $parameters;
    }

    /**
     * Get the related model for a given parameter.
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    protected function getRelatedModel(string $parameter)
    {
        foreach ($this->getRelations() as $relation => $model) {
            if ($relation === $parameter) {
                return $model;
            }
        }

        return null;
    }
}
