<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

trait HasUid
{
    /**
     * Initialize the trait.
     *
     * @return void
     */
    public function initializeHasUid()
    {
        $this->append('uid');
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'uid';
    }

    /**
     * Retrieve the model for a bound value.
     *
     * @param  mixed  $value
     * @param  string|null  $field
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value, $field = null)
    {
        $decodedId = uid()->decode($value);

        return $this->where($this->getKeyName(), $decodedId)->first();
    }

    /**
     * Define the "uid" attribute.
     */
    public function uid(): Attribute
    {
        return new Attribute(fn () => uid($this->id));
    }
}
