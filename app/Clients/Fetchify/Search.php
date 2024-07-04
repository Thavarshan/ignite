<?php

namespace App\Clients\Fetchify;

use Geocoder\Query\GeocodeQuery;
use Illuminate\Support\Str;

class Search
{
    /**
     * The search results.
     *
     * @var array
     */
    protected array $results = [];

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
        $query = GeocodeQuery::create($query);

        $results = $this->client
            ->post('/find', [
                'key' => $this->client->getKeys()['find'],
                'query' => $query->getText(),
                'country' => Str::lower($query->getLocale() ?? 'gbr'),
                'extra' => [
                    'exclude_pobox' => true,
                    'no_groupings' => true,
                ],
            ])
            ->json('results');

        collect($results)->each(function (array $data) use ($query) {
            $this->results[] = $this->client->post('/retrieve', [
                'key' => $this->client->getKeys()['find'],
                'id' => $data['id'],
                'country' => Str::lower($query->getLocale() ?? 'gbr'),
            ])->json('result');
        })->all();

        return $this->results;
    }

    /**
     * Get the search results.
     *
     * @return array
     */
    public function getResults(): array
    {
        return $this->results;
    }

    /**
     * Set the search results.
     *
     * @param array $results
     *
     * @return self
     */
    public function setResults(array $results): self
    {
        $this->results = $results;

        return $this;
    }
}
