<?php

namespace Ignite\Search\Engines;

use Ignite\Interfaces\Search\Manifest as ManifestInterface;

class Manifest implements ManifestInterface
{
    /**
     * The list of all searchable models.
     *
     * @var array
     */
    protected array $models = [];

    /**
     * Get the value of models.
     *
     * @return array
     */
    public function getModels(): array
    {
        return $this->models;
    }

    /**
     * Set the value of models.
     *
     * @param array $models
     *
     * @return self
     */
    public function setModels(array $models): self
    {
        $this->models = array_merge($this->models, $models);

        return $this;
    }
}
