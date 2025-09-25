<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait HasSlug
{
    /**
     * Boot the trait and add model event listeners.
     */
    protected static function bootHasSlug(): void
    {
        static::creating(function (Model $model) {
            if (empty($model->{$model->getSlugColumn()})) {
                $model->generateSlug();
            }
        });

        static::updating(function (Model $model) {
            if ($model->isDirty($model->getSlugSourceColumn())) {
                $model->generateSlug();
            }
        });
    }

    /**
     * Generate a unique slug for the model.
     */
    public function generateSlug(): void
    {
        $slug = Str::slug($this->{$this->getSlugSourceColumn()});
        $originalSlug = $slug;
        $counter = 1;

        while (static::where($this->getSlugColumn(), $slug)->where('id', '!=', $this->id ?? 0)->exists()) {
            $slug = "{$originalSlug}-{$counter}";
            $counter++;
        }

        $this->{$this->getSlugColumn()} = $slug;
    }

    public function getSlugColumn(): string
    {
        return property_exists($this, 'slugColumn') ? $this->slugColumn : 'slug';
    }

    /**
     * Determine the source column for generating the slug.
     */
    public function getSlugSourceColumn(): string
    {
        if (property_exists($this, 'slugSourceColumn')) {
            return $this->slugSourceColumn;
        }

        if ($this->hasAttribute('title')) {
            return 'title';
        }

        if ($this->hasAttribute('name')) {
            return 'name';
        }

        return 'title';
    }

    /**
     * Check if the model has a specific attribute.
     */
    public static function findBySlug(string $slug): ?static
    {
        return static::where((new static)->getSlugColumn(), $slug)->first();
    }

    /**
     * Check if the model has a specific attribute.
     */
    public static function findBySlugOrFail(string $slug): static
    {
        return static::where((new static)->getSlugColumn(), $slug)->firstOrFail();
    }
}
