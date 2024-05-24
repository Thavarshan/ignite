<?php

namespace App\Clients\AWS;

use App\Clients\Client as HttpClient;
use App\Clients\Factory as Http;
use Aws\Handler\GuzzleV6\GuzzleHandler;
use Aws\Sdk;
use InvalidArgumentException;

/**
 * @method \Aws\Result createClient(string $name, array $args = [])
 * @method \Aws\Result putObject(array $args = [])
 */
class Client extends HttpClient
{
    /**
     * The AWS SDK for PHP object.
     *
     * @var \Aws\Sdk
     */
    protected $sdk;

    /**
     * Create a new AWS client.
     *
     * @param \App\Clients\Factory $http
     *
     * @return void
     */
    public function __construct(Http $http, array $config = [])
    {
        parent::__construct(
            $http,
            null,
            $config['credentials']['secret'],
        );

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
            return $this->sdk->$method(...$args);
        }
    }

    /**
     * Get the underlying AWS SDK for PHP object.
     *
     * @return \Aws\Sdk
     */
    public function getSdk(): Sdk
    {
        return $this->sdk;
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
        return tap(new Sdk(array_merge($config, [
            'http_handler' => new GuzzleHandler($this->getHttpClient()),
        ])), function (Sdk $sdk) {
            $this->setApiUrl($sdk->createClient('ec2')->getEndpoint());

            $this->sdk = $sdk;
        });
    }
}
