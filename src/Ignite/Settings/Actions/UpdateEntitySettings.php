<?php

namespace Ignite\Settings\Actions;

use Ignite\Interfaces\Settings\HasSettings;
use Ignite\Interfaces\Settings\UpdatesEntitySettings;

class UpdateEntitySettings implements UpdatesEntitySettings
{
    /**
     * Update the specified resource's settings in storage.
     *
     * @param \Ignite\Interfaces\Settings\HasSettings $entity
     * @param array                                   $data
     * @param array|null                              $options
     *
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter
     */
    public function update(
        HasSettings $entity,
        array $data,
        ?array $options = []
    ): void {
        $entity->updateSettings(
            $this->parseEntitySettings($entity, $data)
        );
    }

    /**
     * Parse the entity settings data.
     *
     * @param \Ignite\Interfaces\Settings\HasSettings $entity
     * @param array                                   $data
     *
     * @return array
     */
    protected function parseEntitySettings(HasSettings $entity, array $data): array
    {
        return collect($data)
            ->only(collect($entity->getDefaultSettings())->keys())
            ->filter(fn ($item) => ! is_null($item))
            ->toArray();
    }
}
