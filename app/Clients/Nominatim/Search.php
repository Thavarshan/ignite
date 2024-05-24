<?php

namespace App\Clients\Nominatim;

class Search
{
    /**
     * The default limit for search results.
     *
     * @var int
     */
    public const DEFAULT_LIMIT = 5;

    /**
     * Create a new search instance.
     *
     * @param \App\Clients\Nominatim\Client $client
     *
     * @return void
     */
    public function __construct(protected Client $client)
    {
    }

    /**
     * Search for locations similar to the given string.
     *
     * @param string $query
     *
     * @return array
     */
    public function __invoke(string $query): array
    {
        return $this->client
            ->get($this->buildUrl($query))
            ->json();
    }

    /**
     * Build the search URL.
     *
     * @param string $query
     *
     * @return string
     */
    protected function buildUrl(string $query): string
    {
        return '/search?' . http_build_query([
            'format' => 'jsonv2',
            'q' => $query,
            'addressdetails' => 1,
            'extratags' => 1,
            'limit' => self::DEFAULT_LIMIT,
        ], '', '&', \PHP_QUERY_RFC3986);
    }
}
