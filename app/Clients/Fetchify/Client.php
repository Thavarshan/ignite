<?php

namespace App\Clients\Fetchify;

use App\Clients\Client as HttpClient;
use App\Clients\Factory as Http;
use Ignite\Interfaces\Locations\LookupClient;
use InvalidArgumentException;
use RuntimeException;

/**
 * @method \App\Clients\Fetchify\Search search(string $query)
 */
class Client extends HttpClient implements LookupClient
{
    /**
     * The resource class map.
     *
     * @var array
     */
    protected array $classMap = [
        'search' => Search::class,
    ];

    /**
     * The API keys for both "find" and "geocode" API endpoints.
     *
     * @var array
     */
    protected array $keys = [];

    /**
     * Create a new KRI client.
     *
     * @param \App\Clients\Factory $http
     * @param array                $config
     *
     * @return void
     */
    public function __construct(Http $http, array $config)
    {
        parent::__construct($http, $config['url'], null);

        $this->build($http, $config);
    }

    /**
     * Dynamically pass method calls to the HTTP client.
     *
     * @param string $method
     * @param array  $args
     *
     * @return mixed
     */
    public function __call($method, $args)
    {
        try {
            return parent::__call($method, $args);
        } catch (InvalidArgumentException $e) {
            return $this->handleResourceCall($method)(...$args);
        }
    }

    /**
     * Build a custom instance of the client.
     *
     * @param \App\Clients\Factory $http
     * @param array                $config
     *
     * @return mixed
     */
    public function build(Http $http, array $config): mixed
    {
        $this->keys = $config['keys'];

        return $this;
    }

    /**
     * Get the API keys for both "find" and "geocode" API endpoints.
     *
     * @return array
     */
    public function getKeys(): array
    {
        return $this->keys;
    }

    /**
     * Set the API keys for both "find" and "geocode" API endpoints.
     *
     * @param array $keys
     *
     * @return self
     */
    public function setKeys(array $keys): self
    {
        $this->keys = $keys;

        return $this;
    }

    /**
     * Handle the resource method call.
     *
     * @param string $method
     *
     * @return mixed
     */
    protected function handleResourceCall(string $method): mixed
    {
        $this->validateResourceMethod($method);

        return $this->makeResource($method);
    }

    /**
     * Validate the resource method.
     *
     * @param string $method
     *
     * @return void
     */
    protected function validateResourceMethod(string $method): void
    {
        if (! isset($this->classMap[$method])) {
            throw new RuntimeException("Resource [{$method}] does not exist.");
        }
    }

    /**
     * Make a new resource instance.
     *
     * @param string $method
     *
     * @return mixed
     */
    protected function makeResource(string $method): mixed
    {
        return $this->make($this->classMap[$method], [
            'client' => $this,
        ]);
    }
}
