<?php

namespace App\Clients\OSRM;

use App\Clients\Client as HttpClient;
use App\Clients\Factory as Http;

class Client extends HttpClient
{
    /**
     * The conversion factor from kilometers to miles.
     */
    public const MILE = 0.621371;

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
        parent::__construct(
            $http,
            $config['url'],
            null
        );

        $this->build($http, $config);
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
        $this->http = $http->withUrlParameters([
            'apiUrl' => $this->getApiUrl(),
            'service' => $config('service'),
            'version' => $config('version'),
            'topic' => $config('topic'),
            'routingProfile' => $config('routingProfile'),
        ]);

        return $this;
    }

    /**
     * Calculate the distance between two locations.
     *
     * @param array $source
     * @param array $destination
     *
     * @return float
     */
    public function calculate(array $source, array $destination): float
    {
        $data = $this->http
            ->get($this->buildUrl($source, $destination))
            ->json();

        if (! blank($data['routes'][0]['distance'])) {
            $distanceMeters = $data['routes'][0]['distance'];
            $distanceKilometers = $distanceMeters / 1000;

            return (float) $distanceKilometers * self::MILE;
        }

        return 0;
    }

    /**
     * Build the URL for the OSRM API.
     *
     * @param array $source
     * @param array $destination
     *
     * @return string
     */
    protected function buildUrl(array $source, array $destination): string
    {
        $source = "{$source['coordinates']['longitude']},{$source['coordinates']['latitude']}"; // phpcs:ignore
        $destination = "{$destination['coordinates']['longitude']},{$destination['coordinates']['latitude']}"; // phpcs:ignore

        return "{+apiUrl}/{service}/{version}/{routingProfile}/{$source};{$destination}"; // phpcs:ignore
    }
}
