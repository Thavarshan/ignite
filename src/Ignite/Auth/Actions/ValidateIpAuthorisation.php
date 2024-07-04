<?php

namespace Ignite\Auth\Actions;

use App\Support\Loggable;
use Ignite\Auth\Exceptions\AuthenticationException;
use Ignite\Interfaces\Auth\IpService;
use Ignite\Interfaces\Auth\ValidatesIpAuthorisation;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ValidateIpAuthorisation implements ValidatesIpAuthorisation
{
    use Loggable;

    /**
     * Create a new auth action instance.
     *
     * @param \Ignite\Interfaces\Auth\IpService $service
     *
     * @return void
     */
    public function __construct(protected readonly IpService $service)
    {
    }

    /**
     * Validate the given IP address.
     *
     * @param string $ip
     * @param array  $ranges
     *
     * @return void
     *
     * @throws \Ignite\Auth\Exceptions\AuthenticationException|\Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function validate(string $ip, array $ranges): void
    {
        $this->checkIpWithinRange($ip, $ranges);

        $this->checkIpAuthorised($ip);
    }

    /**
     * Check if the IP address is within the range.
     *
     * @param string $ip
     * @param array  $ranges
     *
     * @return void
     *
     * @throws \Ignite\Auth\Exceptions\AuthenticationException
     */
    protected function checkIpWithinRange(string $ip, array $ranges): void
    {
        if (blank($ranges)) {
            return;
        }

        collect($ranges)->each(function ($range) use ($ip) {
            if (! filter_var($ip, \FILTER_VALIDATE_IP)) {
                throw AuthenticationException::invalidIp($ip);
            }

            $range = explode('-', $range);

            if (count($range) !== 2) {
                throw AuthenticationException::invalidIpRange();
            }

            $start = ip2long($range[0]);
            $end = ip2long($range[1]);
            $ip = ip2long($ip);

            if ($ip < $start || $ip > $end) {
                throw AuthenticationException::ipNotAuthorised($ip);
            }
        });
    }

    /**
     * Check if the IP address is authorised.
     *
     * @param string $ip
     *
     * @return void
     *
     * @throws \Ignite\Auth\Exceptions\AuthenticationException
     */
    protected function checkIpAuthorised(string $ip): void
    {
        $unauthorised = false;

        try {
            /** @var \Ignite\Interfaces\Auth\Ip $ip */
            $ip = $this->service->findBy('ip', $ip);

            $unauthorised = ! $ip->isAuthorised();
        } catch (ModelNotFoundException $e) {
            $unauthorised = true;

            $this->logError($e->getMessage(), ['ip' => $ip]);
        }

        if ($unauthorised) {
            throw AuthenticationException::ipNotAuthorised();
        }
    }
}
