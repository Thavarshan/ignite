<?php

namespace App\Providers;

use App\Clients\AWS\Client as AWS;
use App\Clients\Factory as HttpFactory;
use App\Clients\KRI\Client as KRI;
use Aws\AwsClientInterface;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware as GuzzleMiddleware;
use Illuminate\Support\ServiceProvider;
use Kevinrob\GuzzleCache\CacheMiddleware;
use Kevinrob\GuzzleCache\Storage\LaravelCacheStorage;
use Kevinrob\GuzzleCache\Strategy\GreedyCacheStrategy;
use Psr\Log\LogLevel;

class ClientServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        // $this->registerHttpClientFactoryMacros();
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerCustomGuzzleClient();
        $this->registerHttpClient();

        $this->registerAwsSdk();
        $this->registerKriClient();
    }

    /**
     * Register the AWS SDK.
     *
     * @return void
     */
    protected function registerAwsSdk(): void
    {
        $this->app->singleton(AWS::class, function ($app) {
            $client = new AWS(
                $app->make(HttpFactory::class),
                $app['config']->get('aws'),
            );

            $app->instance(AwsClientInterface::class, $client->getSdk());

            return $client;
        });
    }

    /**
     * Register the KRI client.
     *
     * @return void
     */
    protected function registerKriClient(): void
    {
        $this->app->singleton(KRI::class, function ($app) {
            return new KRI(
                $app->make(HttpFactory::class),
                $app['config']->get('services.kri'),
            );
        });
    }

    /**
     * Register the custom Guzzle client.
     *
     * @return void
     */
    protected function registerCustomGuzzleClient(): void
    {
        $this->app->singleton(ClientInterface::class, function () {
            $stack = tap(HandlerStack::create(), function (HandlerStack $stack) {
                $stack->push(
                    $this->createGreedyCacheMiddleware(),
                    'greedy-cache'
                );

                $stack->push(
                    $this->createLoggingMiddleware(),
                    'logging'
                );
            });

            return new Client(['handler' => $stack]);
        });
    }

    /**
     * Register the HTTP client.
     *
     * @return void
     */
    protected function registerHttpClient(): void
    {
        $this->app->singleton(HttpFactory::class, function ($app) {
            $factory = new HttpFactory($app['events']);

            $client = $app->make(ClientInterface::class);

            $factory->setHandlerStack($client->getConfig('handler'));

            return $factory;
        });
    }

    /**
     * Create the Guzzle cache middleware.
     *
     * @return \Kevinrob\GuzzleCache\CacheMiddleware
     */
    protected function createGreedyCacheMiddleware(): CacheMiddleware
    {
        return new CacheMiddleware(
            new GreedyCacheStrategy(
                new LaravelCacheStorage($this->app['cache']->store()),
                432000 + random_int(0, 172800) // Random TTL of 5 to 7 days
            )
        );
    }

    /**
     * Create the logging middleware.
     *
     * @return callable
     */
    protected function createLoggingMiddleware(): callable
    {
        return GuzzleMiddleware::log(
            $this->app['log']->getLogger(),
            new MessageFormatter('REQUEST: {method} {uri} HTTP/{version} {req_headers} {req_body} | RESPONSE: {res_headers}'), // phpcs:ignore
            LogLevel::DEBUG
        );
    }
}
