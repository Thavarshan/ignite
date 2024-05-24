<?php

namespace App\Support;

use Illuminate\Support\Arr as IlluminateArr;

class Arr extends IlluminateArr
{
    /**
     * Determine if the given arrays match.
     *
     * @param array $array1
     * @param array $array2
     * @param array $keys
     * @param int   $threshold (1-5, 1 being the most lenient and 5 being the most strict.)
     *
     * @return bool
     */
    public static function match(
        array $array1,
        array $array2,
        array $keys,
        $threshold = 5
    ): bool {
        $matches = 0;

        foreach ($keys as $key) {
            if (! isset($array1[$key]) || ! isset($array2[$key])) {
                // Key missing in one of the arrays, consider as not matched
                continue;
            }

            if (strcasecmp(trim($array1[$key]), trim($array2[$key])) === 0) {
                $matches++;
            }
        }

        $totalKeys = count($keys);
        $matchPercentage = $matches / $totalKeys * 100;

        // Convert the threshold to a percentage range for comparison
        $thresholdPercentage = match ($threshold) {
            1 => 20, // Most lenient: at least 20% of keys must match
            2 => 40,
            3 => 60,
            4 => 80,
            5 => 100, // Most strict: 100% of keys must match
            default => 100,
        };

        return $matchPercentage >= $thresholdPercentage;
    }
}
