<?php

namespace Ignite\Shared\Traits;

use Ignite\Interfaces\Shared\Sluggable as SluggableInterface;
use Ignite\Shared\Enums\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use LogicException;

trait Sluggable
{
    /**
     * The cache time-to-live (TTL) for the slug cache.
     */
    public const SLUG_CACHE_TTL = 3600;

    /**
     * Generate a unique slug for the model.
     *
     * @return void
     */
    public function generateSlug(): void
    {
        $slugBase = $this->sluggify();
        $slug = $slugBase;
        $counter = 1;

        // Attempt to retrieve existing slugs from cache first
        $cacheKey = $this->getSluggableCacheKey($slugBase);

        // Attempt to retrieve existing slugs that start with the slug base from the cache.
        // If not available in the cache, fetch from the database and cache the result.
        $existingSlugs = Cache::remember(
            $cacheKey,
            self::SLUG_CACHE_TTL,
            function () use ($slugBase) {
                return static::select([$this->getSlugAttributeName()])
                    ->where($this->getSlugAttributeName(), 'LIKE', "{$slugBase}%")
                    ->get()
                    ->pluck($this->getSlugAttributeName())
                    ->toArray();
            }
        );

        while (in_array($slug, $existingSlugs)) {
            $slug = "{$slugBase}-{$counter}";

            $counter++;
        }

        $this->setAttribute($this->getSlugAttributeName(), $slug);

        // Optionally, update the cache with the new slug for future checks
        $existingSlugs[] = $slug;

        Cache::put($cacheKey, $existingSlugs, self::SLUG_CACHE_TTL);
    }

    /**
     * Get the name of the field that should be used to generate the slug.
     *
     * @return string
     */
    public function getSluggableField(): string
    {
        if (property_exists($this, 'sluggableField')) {
            return $this->sluggableField;
        }

        $attribute = collect([
            Attribute::NAME->value,
            Attribute::TITLE->value,
        ])->first(function (string $attribute) {
            if (has_attribute($this, $attribute)) {
                return $attribute;
            }
        });

        if (! is_null($attribute)) {
            return $attribute;
        }

        throw new LogicException('A sluggable field is not defined and no suitable fallback was found.');
    }

    /**
     * Create slug from set model attribute.
     *
     * @return string
     */
    public function sluggify(): string
    {
        return Str::slug($this->getSluggableValue());
    }

    /**
     * Find model by slug.
     *
     * @param string $slug
     *
     * @return \Illuminate\Database\Eloquent\Model|\Ignite\Interfaces\Shared\Sluggable
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findBySlug(string $slug): Model|SluggableInterface
    {
        return static::query()
            ->where($this->getSlugAttributeName(), $slug)
            ->firstOrFail();
    }

    /**
     * Get the value of the model's slug.
     *
     * @return string
     */
    public function getSlug(): string
    {
        return $this->getAttribute($this->getSlugAttributeName());
    }

    /**
     * Boot the sluggable trait.
     *
     * @return void
     */
    protected static function bootSluggable(): void
    {
        static::creating(function (SluggableInterface $model) {
            $model->generateSlug();
        });

        static::updating(function (SluggableInterface $model) {
            /** @var \Illuminate\Database\Eloquent\Model $model */
            if ($model->isDirty($model->getSluggableField())) {
                $model->generateSlug();
            }
        });
    }

    /**
     * Get the value that should be used to generate the slug.
     *
     * @return string
     */
    protected function getSluggableValue(): string
    {
        return $this->{$this->getSluggableField()};
    }

    /**
     * Get the name of the attribute that holds the slug.
     *
     * @return string
     */
    protected function getSlugAttributeName(): string
    {
        return property_exists($this, 'slugAttributeName')
            ? $this->slugAttributeName
            : Attribute::SLUG->value;
    }

    /**
     * Generate a unique cache key for the slug base.
     *
     * @param string $slugBase
     *
     * @return string
     */
    protected function getSluggableCacheKey(string $slugBase): string
    {
        return 'sluggable_' . $slugBase;
    }
}
