<?php

namespace Ignite\Settings\Models\Traits;

use Ignite\Interfaces\Settings\ConfigurationRepository as ConfigurationsRepositoryInterface;
use Ignite\Interfaces\Settings\HasSettings as HasSettingsInterface;
use Ignite\Interfaces\Settings\SettingsFilter;
use Ignite\Settings\Models\Settings;
use Ignite\Settings\Repositories\ConfigurationRepository;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Str;

trait HasSettings
{
    /**
     * The model settings.
     *
     * @var \Ignite\Interfaces\Settings\ConfigurationRepository
     */
    protected $settingsItems;

    /**
     * Indicate if the default settings have been applied.
     *
     * @var bool
     */
    protected $defaultSettingsApplied = false;

    /**
     * Boot the trait.
     *
     * @return void
     */
    public static function bootHasSettings(): void
    {
        static::retrieved(function (HasSettingsInterface $model) {
            $model->retrieveSettings();
        });

        static::created(function (HasSettingsInterface $model) {
            $model->applyDefaultSettings();
        });
    }

    /**
     * Retrieve all model settings.
     *
     * @return void
     */
    public function retrieveSettings(): void
    {
        $this->settingsItems = new ConfigurationRepository(
            optional($this->settings)->getItems() ?? []
        );
    }

    /**
     * Fetch the settings relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function settings(): MorphOne
    {
        return $this->morphOne(Settings::class, 'subject');
    }

    /**
     * Get the subject id.
     *
     * @return int|string
     */
    public function getSubjectId(): int|string
    {
        return $this->getId();
    }

    /**
     * Determine if the entity has a setting.
     *
     * @param string $key
     *
     * @return bool
     */
    public function hasSetting(string $key): bool
    {
        return $this->settingsItems->has($key);
    }

    /**
     * Get all settings.
     *
     * @param \Ignite\Interfaces\Settings\SettingsFilter|null $filter
     *
     * @return \Ignite\Interfaces\Settings\ConfigurationRepository
     */
    public function getSettings(
        ?SettingsFilter $filter = null
    ): ConfigurationsRepositoryInterface {
        if (is_null($this->settingsItems)) {
            $this->retrieveSettings();
        }

        return $this->settingsItems->filtered($filter);
    }

    /**
     * Get a setting.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getSetting(string $key, mixed $default = null): mixed
    {
        return $this->getSettings()->get($key, $default);
    }

    /**
     * Set a setting.
     *
     * @param string $key
     * @param mixed  $items
     *
     * @return void
     */
    public function setSetting(string $key, mixed $items): void
    {
        $this->updateSettings([$key => $items]);
    }

    /**
     * Set multiple settings.
     *
     * @param array $settings
     *
     * @return void
     */
    public function updateSettings(array $settings): void
    {
        $items = $this->getSettings()->all();

        if (! blank($items)) {
            $this->settings()
                ->update(['items' => array_filter(
                    array_merge($items, $settings),
                    fn ($item) => ! is_null($item)
                ),
                ]);

            return;
        }

        $this->settings()->create(['items' => $settings]);

        $this->retrieveSettings();
    }

    /**
     * Apply default settings.
     *
     * @return void
     */
    public function applyDefaultSettings(): void
    {
        $settings = $this->getDefaultSettings();

        if (! $settings || $this->defaultSettingsHaveBeenApplied()) {
            return;
        }

        $this->updateSettings($settings);

        $this->defaultSettingsApplied = true;
    }

    /**
     * Get the default settings.
     *
     * @return array|null
     */
    public function getDefaultSettings(): ?array
    {
        return with(class_basename($this), function (string $entity): array {
            return config('defaults.settings.' . Str::of($entity)->plural()->lower());
        });
    }

    /**
     * Determine if the default settings have been applied.
     *
     * @return bool
     */
    public function defaultSettingsHaveBeenApplied(): bool
    {
        return $this->defaultSettingsApplied;
    }
}
