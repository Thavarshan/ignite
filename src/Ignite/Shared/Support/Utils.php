<?php

namespace Ignite\Shared\Support;

class Utils
{
    /**
     * Construct full name of a user using their first name,
     * optional middle names and last name.
     *
     * @param string      $firstName
     * @param string|null $middleNames
     * @param string      $lastName
     *
     * @return string
     */
    public static function name(
        string $firstName,
        string $lastName,
        ?string $middleNames = null,
    ): string {
        $fullName = $firstName;

        if ($middleNames) {
            $fullName .= ' ' . $middleNames;
        }

        $fullName .= ' ' . $lastName;

        return $fullName;
    }
}
