<?php

namespace Ignite\Shared\Traits;

use Ignite\Interfaces\Activities\Trackable;
use Ignite\Interfaces\Shared\Hashable as HashableInterface;
use Ignite\Shared\Enums\Attribute;
use Ignite\Shared\Support\HashId;
use Illuminate\Support\Arr;

trait Hashable
{
    /**
     * Boot all of the bootable traits on the model.
     *
     * @return void
     */
    public static function bootHashable(): void
    {
        static::created(function (HashableInterface $model) {
            if ($model instanceof Trackable) {
                $model->withoutTracking(function () use ($model) {
                    $model->generateHashId();
                });

                return;
            }

            $model->generateHashId();
        });
    }

    /**
     * Generate a new and unique code
     *
     * @return void
     */
    public function generateHashId(): void
    {
        $this->forceFill([
            Attribute::UID->value => sprintf(
                '%s-%s',
                $this->getPrefix(),
                HashId::generate($this->getId())
            ),
        ])->saveQuietly();
    }

    /**
     * Get the entity's unique identifier prefix.
     *
     * @return string
     */
    public function getPrefix(): string
    {
        if (! isset($this->hashIdPrefix)) {
            return $this->getTable();
        }

        return $this->hashIdPrefix;
    }

    /**
     * Get the entity's unique identifier.
     *
     * @return string|null
     */
    public function getUid(): ?string
    {
        return $this->getAttribute(Attribute::UID->value);
    }

    /**
     * Get the entity's unique identifier.
     *
     * @return string
     */
    public function revealHashedId(): string
    {
        $hash = Arr::last(explode('-', $this->getUid()));

        return HashId::decode($hash)[0];
    }
}
