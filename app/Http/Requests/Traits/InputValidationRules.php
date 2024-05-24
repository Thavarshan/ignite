<?php

namespace App\Http\Requests\Traits;

use Illuminate\Support\Arr;

trait InputValidationRules
{
    /**
     * Get list of validation rules for specific category.
     *
     * @param string $category
     *
     * @return array
     */
    public function getRules(string $category): array
    {
        return config("rules.{$category}", []);
    }

    /**
     * Get validation rules for specified validation category.
     *
     * @param string|array $validationCategory
     * @param array        $additionalRules
     *
     * @return array
     */
    protected function getRulesFor(
        string|array $validationCategory,
        array $additionalRules = []
    ): array {
        $rules = [];

        if (is_string($validationCategory)) {
            $validationCategory = Arr::wrap($validationCategory);
        }

        foreach ($validationCategory as $category) {
            $rules = array_merge($rules, $this->getRules($category));
        }

        return array_merge($rules, $additionalRules);
    }
}
