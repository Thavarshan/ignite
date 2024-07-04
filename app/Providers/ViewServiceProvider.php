<?php

namespace App\Providers;

use App\Http\Responses\Response;
use App\View\Factory;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Support\ServiceProvider;
use Inertia\ResponseFactory as InertiaResponseFactory;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->registerViewFactory();
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
    }

    /**
     * Register the view factory.
     *
     * @return void
     */
    protected function registerViewFactory(): void
    {
        // The custom view factory is registered contextually in the Response class
        // so that Laravel's default error views can be rendered to render HTTP
        // exceptions with the default blade views instead of Inertia's views.
        $factory = new Factory($this->app['view.finder']);

        $this->app->instance(InertiaResponseFactory::class, $factory);

        $this->app->when(Response::class)
            ->needs(ViewFactory::class)
            ->give(fn () => $factory);
    }
}
