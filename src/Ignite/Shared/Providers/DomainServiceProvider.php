<?php

namespace Ignite\Shared\Providers;

use Ignite\Interfaces\Shared\Entity;
use Ignite\Shared\Enums\Resource;
use Ignite\Shared\Support\TextPreprocessor;
use Illuminate\Support\Env;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Features as FortifyFeatures;
use Laravel\Jetstream\Features as JetstreamFeatures;
use Laravel\Pennant\Feature;

class DomainServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->defineFeatures();

        $this->registerCurrentlyConsideredEntity();

        $this->registerTextPreprocessor();
    }

    /**
     * Register the resource that was resolved by the route model
     * binder as the currently considered entity.
     *
     * @return void
     */
    public function registerCurrentlyConsideredEntity(): void
    {
        $this->app->bind(Entity::class, function ($app) {
            $resource = collect($app['request']->route()->parameterNames())
                ->filter(fn (string $param) => Resource::isValid($param))
                ->first();

            return $app['request']->route($resource);
        });
    }

    /**
     * Register the application's auth features.
     *
     * @return void
     */
    public function registerAuthFeatures(): void
    {
        Feature::define(
            'email-verification',
            FortifyFeatures::enabled(FortifyFeatures::emailVerification())
        );

        Feature::define(
            'reset-passwords',
            FortifyFeatures::enabled(FortifyFeatures::resetPasswords())
        );

        Feature::define(
            'update-profile-information',
            FortifyFeatures::enabled(FortifyFeatures::updateProfileInformation())
        );

        Feature::define(
            'two-factor-authentication',
            FortifyFeatures::enabled(FortifyFeatures::twoFactorAuthentication())
        );

        Feature::define(
            'update-passwords',
            FortifyFeatures::enabled(FortifyFeatures::updatePasswords())
        );

        Feature::define(
            'api-tokens',
            JetstreamFeatures::enabled(JetstreamFeatures::api())
        );
    }

    /**
     * Register the application's search features.
     *
     * @return void
     */
    public function registerSearchFeatures(): void
    {
        Feature::define('search', Env::get('SEARCH_ENABLED', true)); // global search
    }

    /**
     * Register the application's reporting features.
     *
     * @return void
     */
    public function registerGeocodingFeatures(): void
    {
        Feature::define('geocoding', Env::get('GEOCODER_ENABLED', true));
    }

    /**
     * Register optinal features that can be toggled on or off.
     *
     * @return void
     */
    protected function defineFeatures(): void
    {
        $this->registerAuthFeatures();
        $this->registerSearchFeatures();
        $this->registerGeocodingFeatures();
    }

    /**
     * Register TextPreprocessor services.
     *
     * @return void
     */
    protected function registerTextPreprocessor(): void
    {
        $this->app->bind(TextPreprocessor::class, function ($app) {
            return new TextPreprocessor(
                $app['config']['text-preprocessing']['stopwords']
            );
        });
    }
}
