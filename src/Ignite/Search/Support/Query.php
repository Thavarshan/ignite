<?php

namespace Ignite\Search\Support;

use Ignite\Interfaces\Search\Query as QueryInterface;

class Query implements QueryInterface
{
    /**
     * The array of query parameters.
     *
     * @var array
     */
    protected $query = [];

    /**
     * The query string.
     *
     * @var string
     */
    protected $queryString;

    /**
     * The array of query parameters to blacklist.
     *
     * @var array
     */
    protected $blacklist = [
        'paginate',
        'page',
        'per_page',
        'sort',
        'order',
    ];

    /**
     * Query constructor.
     *
     * @param string $query
     *
     * @return void
     */
    public function __construct(string $query)
    {
        $this->setQuery($query);
    }

    /**
     * Set the query.
     *
     * @param string $query
     *
     * @return void
     */
    public function setQuery(string $query): void
    {
        $this->parse($query);
    }

    /**
     * Get the array of query parameters.
     *
     * @return array
     */
    public function getQuery(): array
    {
        return $this->query;
    }

    /**
     * Get the value of queryString
     *
     * @return string
     */
    public function getQueryString(): string
    {
        return $this->queryString;
    }

    /**
     * Set the value of queryString
     *
     * @param string $queryString
     *
     * @return self
     */
    public function setQueryString(string $queryString): self
    {
        $this->queryString = $queryString;

        return $this;
    }

    /**
     * Parse the query string into an array of parameters.
     *
     * @param string $query
     *
     * @return array
     */
    protected function parse(string $query): array
    {
        // A query string is a string of key-value pairs separated by an ampersand (&).
        // Each key-value pair is separated by an equals sign (=).
        // For example: key1=value1&key2=value2&key3=value3
        // We can use the PHP function parse_str() to parse the query string into an array.
        // The parse_str() function parses a query string into variables.
        // The variables are stored as an associative array.
        // Check if the query string starts with '?', remove it if present
        return transform($query[0] === '?'
            ? substr($query, 1)
            : $query, function ($query) {
                if (! preg_match('/[=?&]/', $query) || ! preg_match('/^(q=)/', $query)) {
                    $query = sprintf('q=%s', ltrim($query, '='));
                }

                $this->setQueryString($query);

                parse_str($query, $this->query);

                $this->query = reverse_filter_attributes($this->query, $this->blacklist);

                return $this->query;
            });
    }
}
