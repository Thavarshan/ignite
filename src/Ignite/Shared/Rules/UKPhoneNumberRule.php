<?php

namespace Ignite\Shared\Rules;

// phpcs:ignoreFile

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UKPhoneNumberRule extends PhoneNumberRule implements ValidationRule
{
    /**
     * The value pattern to compare against.
     *
     * @return void
     */
    public function __construct()
    {
        // UK phone number regex: https://stackoverflow.com/questions/11518035/regular-expression-for-gb-based-and-only-numeric-phone-number
        // Matches +447222555555 | +44 7222 555 555 | (0722) 5555555 #2222
        // Non-Matches (+447222)555555 | +44(7222)555555  | (0722) 5555555 #22
        static::setPattern('/^(((\+44\s?\d{4}|\(?0\d{4}\)?)\s?\d{3}\s?\d{3})|((\+44\s?\d{3}|\(?0\d{3}\)?)\s?\d{3}\s?\d{4})|((\+44\s?\d{2}|\(?0\d{2}\)?)\s?\d{4}\s?\d{4}))(\s?\#(\d{4}|\d{3}))?$/');
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
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! preg_match(static::getPattern(), $value)) {
            $fail("The {$attribute} must be a valid UK phone number.");
        }
    }
}
