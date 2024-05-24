<?php

namespace App\Clients;

use App\Clients\Factory as Http;
use App\Enums\AppInfo;
use App\Support\InteractsWithContainer;
use App\Support\Loggable;
use GuzzleHttp\ClientInterface;
use InvalidArgumentException;

abstract class Client
{
    use Loggable;
    use InteractsWithContainer;

    /**
     * The HTTP client instance.
     *
     * @var \Illuminate\Http\Client\Factory|\Illuminate\Http\Client\PendingRequest
     */
    protected $http;

    /**
     * The API key.
     *
     * @var string
     */
    protected $apiKey;

    /**
     * The API URL.
     *
     * @var string|null
     */
    protected $apiUrl;

    /**
     * The HTTP client headers.
     *
     * @var array
     */
    protected $headers = [
        'User-Agent' => AppInfo::UserAgent->value,
    ];

    /**
     * The HTTP client options.
     *
     * @var array
     */
    protected $options = [
        'http_errors' => false,
    ];

    /**
     * Create a new client instance.
     *
     * @param \App\Clients\Factory $http
     * @param string|null          $url
     * @param string|null          $token
     *
     * @return void
     */
    public function __construct(Http $http, ?string $url, ?string $token)
    {
        $this->setApiUrl($url)->buildHttpHandler($http, $token);
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
        if (method_exists($this, $method) && is_callable([$this, $method])) {
            return $this->{$method}(...$args);
        }

        if (method_exists($this->http, $method) && is_callable([$this->http, $method])) {
            return $this->http->{$method}(...$args);
        }

        throw new InvalidArgumentException("Method [{$method}] does not exist.");
    }

    /**
     * Build a custom instance of the client.
     *
     * @param \App\Clients\Factory $http
     * @param array                $config
     *
     * @return mixed
     */
    abstract public function build(Http $http, array $config): mixed;

    /**
     * Get the HTTP client headers.
     *
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Set the HTTP client headers.
     *
     * @param array $headers
     *
     * @return self
     */
    public function withHeaders(array $headers): self
    {
        $this->headers = array_merge($this->headers, $headers);

        $this->http->withHeaders($this->headers);

        return $this;
    }

    /**
     * Get the HTTP client options.
     *
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * Set the HTTP client options.
     *
     * @param array $options
     *
     * @return self
     */
    public function withOptions(array $options): self
    {
        $this->options = array_merge($this->options, $options);

        $this->http->withOptions($this->options);

        return $this;
    }

    /**
     * Set the API key/token.
     *
     * @param string $token
     *
     * @return self
     */
    public function withToken(string $token): self
    {
        if (blank($token)) {
            throw new InvalidArgumentException('API token cannot be empty.');
        }

        $this->apiKey = $token;

        $this->http->withToken($this->apiKey);

        return $this;
    }

    /**
     * Set the API URL.
     *
     * @param string|null $url
     *
     * @return self
     */
    public function setApiUrl(?string $url): self
    {
        if (blank($url)) {
            return $this;
        }

        if (filter_var($url, \FILTER_VALIDATE_URL) === false) {
            throw new InvalidArgumentException('Invalid API URL.');
        }

        $this->apiUrl = $url;

        return $this;
    }

    /**
     * Get the API URL.
     *
     * @return string
     */
    public function getApiUrl(): string
    {
        return $this->apiUrl ?? url('/');
    }

    /**
     * Get the API key.
     *
     * @return string|null
     */
    public function getApiKey(): ?string
    {
        return $this->apiKey;
    }

    /**
     * Get the HTTP client instance.
     *
     * @return \GuzzleHttp\ClientInterface
     */
    public function getHttpClient(): ClientInterface
    {
        return $this->make(ClientInterface::class);
    }

    /**
     * Check if the API key is set.
     *
     * @return bool
     */
    protected function apiKeyIsSet(): bool
    {
        return ! is_null($this->apiKey);
    }

    /**
     * Build the HTTP client.
     *
     * @param \App\Clients\Factory $http
     * @param string|null          $token
     *
     * @return self
     */
    protected function buildHttpHandler(Http $http, ?string $token): self
    {
        $this->http = $http->retry(3, 100)
            ->acceptJson()
            ->contentType('application/json')
            ->withOptions($this->getOptions())
            ->withHeaders($this->getHeaders())
            ->baseUrl($this->getApiUrl());

        if ($this->apiKeyIsSet() || ! is_null($token) && is_string($token)) {
            $this->withToken($token ?? $this->getApiKey());
        }

        return $this;
    }
}
