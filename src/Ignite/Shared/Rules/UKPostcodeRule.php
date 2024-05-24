<?php

namespace Ignite\Shared\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UKPostcodeRule extends PostcodeRule implements ValidationRule
{
    /**
     * The value pattern to compare against.
     *
     * @return void
     */
    public function __construct()
    {
        static::setPattern('/^([A-Za-z][A-Ha-hJ-Yj-y]?[0-9][A-Za-z0-9]? ?[0-9][A-Za-z]{2}|[Gg][Ii][Rr] ?0[Aa]{2})$/'); // phpcs:ignore
    }

    /**
     * Run the validation rule.
     *
     * @param string                                                               $attribute
     * @param mixed                                                                $value
     * @param Closure(string): \Illuminate\Translation\PotentiallyTranslatedString $fail
     *
     * @return void
     */
    public function validate(
        string $attribute,
        mixed $value,
        Closure $fail
    ): void {
        //  validate the value against the pattern
        if (! static::validatePattern($value)) {
            $fail(__('validation.uk_postcode', [
                'attribute' => $attribute,
            ]));
        }
    }
}
