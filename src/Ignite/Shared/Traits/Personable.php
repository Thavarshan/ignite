<?php

namespace Ignite\Shared\Traits;

use Ignite\Shared\Support\Utils;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;

trait Personable
{
    /**
     * Get the name of the model.
     *
     * @return string
     */
    public function getName(): string
    {
        return Utils::name(
            firstName: $this->getAttribute('firstname'),
            lastName: $this->getAttribute('lastname'),
            middleNames: $this->getAttribute('middlenames'),
        );
    }

    /**
     * Model accessor method that gets the user's full name.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     *
     * @see https://laravel.com/docs/10.x/eloquent-mutators#accessors-and-mutators
     */
    public function name(): Attribute
    {
        return Attribute::make(get: fn () => $this->getName());
    }

    /**
     * The email of this person.
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email ?? Str::lower($this->getName()) . '@example.com';
    }

    /**
     * Get the customer's email as a link.
     *
     * @return string
     */
    public function getEmailLinkAttribute(): string
    {
        return "mailto:{$this->getEmail()}";
    }

    /**
     * Find the person/entity associated with the given email.
     *
     * @return static|null
     */
    public static function findByEmail(string $email): ?self
    {
        return static::where('email', $email)->first();
    }
}
