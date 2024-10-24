<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait Sluggable
{
    /**
     * Boot the trait and set the slug before saving the model.
     */
    public static function bootSluggable(): void
    {
        static::saving(function ($model) {
            $slugAttribute = $model->getSluggableAttribute(); // Get the attribute to slugify
            $slugColumn = $model->getSlugColumn(); // Get the slug column
            $slug = $model->generateSlug($model->{$slugAttribute});

            // Ensure slug uniqueness
            $model->{$slugColumn} = $model->makeUniqueSlug($slug);
        });
    }

    /**
     * Get the name of the column used to store the slug.
     */
    protected function getSlugColumn(): string
    {
        return property_exists($this, 'slugColumn') ? $this->slugColumn : 'slug';
    }

    /**
     * Get the attribute that will be used to generate the slug.
     */
    protected function getSluggableAttribute(): string
    {
        return property_exists($this, 'sluggableAttribute') ? $this->sluggableAttribute : 'name';
    }

    /**
     * Generate a slug from the given string.
     */
    protected function generateSlug(string $value): string
    {
        // Use Laravel's Str helper to generate the slug
        return Str::slug($value);
    }

    /**
     * Ensure that the slug is unique within the database.
     */
    protected function makeUniqueSlug(string $slug): string
    {
        $slugColumn = $this->getSlugColumn();
        $originalSlug = $slug;
        $counter = 1;

        // Check if the slug already exists
        while ($this->slugExists($slug)) {
            $slug = $originalSlug.'-'.$counter++;
        }

        return $slug;
    }

    /**
     * Check if the given slug already exists in the database.
     */
    protected function slugExists(string $slug): bool
    {
        $slugColumn = $this->getSlugColumn();

        return static::where($slugColumn, $slug)->exists();
    }

    /**
     * Set the slug attribute on the model.
     */
    public function setSlugAttribute(string $value): void
    {
        $this->attributes[$this->getSlugColumn()] = $this->makeUniqueSlug($this->generateSlug($value));
    }
}
