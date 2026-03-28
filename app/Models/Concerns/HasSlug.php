<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait HasSlug
{
    /**
     * Boot the trait.
     */
    public static function bootHasSlug(): void
    {
        static::creating(function (Model $model): void {
            if ($model->hasExplicitSlug()) {
                return;
            }

            $model->generateSlug();
        });

        static::updating(function (Model $model): void {
            if ($model->isDirty($model->getSlugColumn())) {
                return;
            }

            if (! $model->slugShouldBeRegenerated()) {
                return;
            }

            $model->generateSlug();
        });
    }

    /**
     * The model attribute that stores the slug.
     */
    public function getSlugColumn(): string
    {
        return 'slug';
    }

    /**
     * Candidate attributes used to derive the slug source.
     *
     * @return array<int, string>
     */
    public function getSlugSourceColumns(): array
    {
        return ['name', 'title', 'label'];
    }

    /**
     * Determine whether the model already has a slug value set.
     */
    protected function hasExplicitSlug(): bool
    {
        return filled($this->getAttribute($this->getSlugColumn()));
    }

    /**
     * Determine if the slug should be regenerated during update.
     */
    protected function slugShouldBeRegenerated(): bool
    {
        if (! filled($this->getAttribute($this->getSlugColumn()))) {
            return true;
        }

        foreach ($this->getSlugSourceColumns() as $column) {
            if ($this->isDirty($column) && filled($this->getAttribute($column))) {
                return true;
            }
        }

        return false;
    }

    /**
     * Generate a unique slug from the first populated source column.
     */
    protected function generateSlug(): void
    {
        $sourceValue = $this->resolveSlugSourceValue();

        if (! filled($sourceValue)) {
            return;
        }

        $slug = Str::slug($sourceValue);

        if ($slug === '') {
            return;
        }

        $this->setAttribute($this->getSlugColumn(), $this->makeUniqueSlug($slug));
    }

    /**
     * Resolve the source value used to build the slug.
     */
    protected function resolveSlugSourceValue(): ?string
    {
        foreach ($this->getSlugSourceColumns() as $column) {
            $value = $this->getAttribute($column);

            if (filled($value)) {
                return (string) $value;
            }
        }

        return null;
    }

    /**
     * Generate a unique slug for the current model table.
     */
    protected function makeUniqueSlug(string $baseSlug): string
    {
        $slugColumn = $this->getSlugColumn();
        $slug = $baseSlug;
        $suffix = 2;

        while ($this->newQueryWithoutScopes()
            ->where($slugColumn, $slug)
            ->when($this->exists, function (Builder $query): void {
                $query->whereKeyNot($this->getKey());
            })
            ->exists()) {
            $slug = $baseSlug.'-'.$suffix;
            $suffix++;
        }

        return $slug;
    }
}
