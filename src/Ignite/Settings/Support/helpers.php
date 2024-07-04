<?php

use Ignite\Interfaces\Settings\ConfigurationRepository;
use Ignite\Interfaces\Settings\HasSettings;

if (! function_exists('settings')) {
    /**
     * Get / set the specified configuration value.
     *
     * If an array is passed as the key, we will assume you want to set an array of values.
     *
     * @param \Ignite\Interfaces\Settings\HasSettings|string $entity
     * @param array|string|null                              $key
     * @param mixed                                          $default
     *
     * @return mixed
     */
    function settings(
        HasSettings|string $entity = 'organisation',
        array|string|null $key = null,
        mixed $default = null
    ): mixed {
        if ($entity instanceof HasSettings) {
            return settings_from($entity->getSettings(), $key, $default);
        }

        // Only allow either 'user' or 'organisation' as the entity.
        if (! in_array($entity, ['user', 'organisation'])) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Invalid entity "%s" provided. Only "user" and "organisation" are allowed.',
                    $entity
                )
            );
        }

        // Settings of the entity are loaded when the entity is authenticated and is bound via a middleware.
        // So, we should check if the entity is bound to the container.
        if (! app()->bound($entity . '.settings')) {
            throw new \RuntimeException(
                sprintf(
                    'The "%s" entity is not authenticated. Settings cannot be retrieved.',
                    $entity
                )
            );
        }

        // Get the settings repository for the entity.
        return settings_from(app($entity . '.settings'), $key, $default);
    }
}

if (! function_exists('settings_from')) {
    /**
     * Get the settings repository from the specified repository.
     *
     * @param \Ignite\Interfaces\Settings\ConfigurationRepository $repository
     * @param array|string|null                                   $key
     * @param mixed                                               $default
     *
     * @return mixed
     */
    function settings_from(
        ConfigurationRepository $repository,
        array|string|null $key,
        mixed $default
    ): mixed {
        if (is_null($key)) {
            return $repository;
        }

        if (is_array($key)) {
            $repository->set($key);

            return null;
        }

        return $repository->get($key, $default);
    }
}
