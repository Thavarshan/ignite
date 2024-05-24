<?php

namespace Ignite\Interfaces\Shared;

use Illuminate\Database\Eloquent\Model;
use LogicException;

interface Sluggable
{
    /**
     * Generate slug for model.
     *
     * @return void
     */
    public function generateSlug(): void;

    /**
     * Name of attribute that will be used to create the slug from.
     *
     * @return string
     *
     * @throws LogicException
     */
    public function getSluggableField(): string;

    /**
     * Create slug from set model attribute.
     *
     * @return string
     */
    public function sluggify(): string;

    /**
     * Find model by slug.
     *
     * @param string $slug
     *
     * @return \Illuminate\Database\Eloquent\Model|\Ignite\Interfaces\Shared\Sluggable
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findBySlug(string $slug): Model|Sluggable;

    /**
     * Get the value of the model's slug.
     *
     * @return string
     */
    public function getSlug(): string;
}
