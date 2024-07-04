<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Model;
use ReflectionClass;

class ExtractProperties
{
    /**
     * Extract the properties for the given object in array form.
     *
     * The given array is ready for storage.
     *
     * @param mixed $target
     *
     * @return array
     */
    public static function from(mixed $target): array
    {
        return collect((new ReflectionClass($target))->getProperties())
            ->mapWithKeys(function ($property) use ($target) {
                $property->setAccessible(true);

                if (\PHP_VERSION_ID >= 70400 && ! $property->isInitialized($target)) {
                    return [];
                }

                $value = $property->getValue($target);

                if ($value instanceof Model) {
                    return [$property->getName() => FormatModel::given($value)];
                }

                if (is_object($value)) {
                    return [
                        $property->getName() => [
                            'class' => $value::class,
                            'properties' => json_decode(json_encode($value), true),
                        ],
                    ];
                }

                return [$property->getName() => json_decode(json_encode($value), true)];
            })->toArray();
    }
}
