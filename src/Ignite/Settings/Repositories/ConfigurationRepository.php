<?php

namespace Ignite\Settings\Repositories;

use Ignite\Interfaces\Settings\ConfigurationRepository as ConfigurationRepositoryInterface;
use Ignite\Interfaces\Settings\SettingsFilter;
use Illuminate\Config\Repository;
use Illuminate\Support\Arr;

class ConfigurationRepository extends Repository implements ConfigurationRepositoryInterface
{
    /**
     * Fetch all relevant filtered values from the repository.
     *
     * @param \Ignite\Interfaces\Settings\SettingsFilter|null $filter
     *
     * @return self
     */
    public function filtered(?SettingsFilter $filter = null): self
    {
        if (is_null($filter)) {
            return $this;
        }

        $keys = array_values($filter->getFilters());

        if (blank($keys)) {
            return $this;
        }

        return new self(Arr::only($this->all(), $keys));
    }
}
