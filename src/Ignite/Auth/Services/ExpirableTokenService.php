<?php

namespace Ignite\Auth\Services;

use Carbon\Carbon;
use Ignite\Auth\Tokens\ExpirableToken;
use Ignite\Interfaces\Auth\ExpirableToken as ExpirableTokenInterface;
use Ignite\Interfaces\Auth\ExpirableTokenService as ExpirableTokenServiceInterface;
use Illuminate\Contracts\Encryption\Encrypter;

class ExpirableTokenService implements ExpirableTokenServiceInterface
{
    /**
     * The expiration timestamp.
     *
     * Should expire in 2 days by default. So, 3 days including the day of creation.
     *
     * @var int
     */
    protected int $expiresIn = 2;

    /**
     * Create a new token instance.
     *
     * @param \Illuminate\Contracts\Encryption\Encrypter $encrypter
     * @param int|null                                   $expiresIn
     *
     * @return void
     */
    public function __construct(
        protected Encrypter $encrypter,
        ?int $expiresIn = null
    ) {
        if (! is_null($expiresIn)) {
            $this->expiresIn = $expiresIn;
        }
    }

    /**
     * Create a new token instance.
     *
     * @param string $name
     *
     * @return \Ignite\Interfaces\Auth\ExpirableToken
     */
    public function encrypt(string $name): ExpirableTokenInterface
    {
        // Get current timestamp
        $timestamp = Carbon::now()->addDays($this->expiresIn);

        // Concatenate name and timestamp
        $plainTextValue = $name . '|' . $timestamp;

        // Encrypt the string
        $encrypted = $this->encrypter->encrypt($plainTextValue);

        // Convert to Base64 URL-safe
        $token = strtr(base64_encode($encrypted), '+/', '-_');

        return new ExpirableToken($token, $plainTextValue, $timestamp);
    }

    /**
     * Decrypt a token instance.
     *
     * @param string $token
     *
     * @return \Ignite\Interfaces\Auth\ExpirableToken
     *
     * @phpcsSuppress SlevomatCodingStandard.Variables.UnusedVariable
     */
    public function decrypt(string $token): ExpirableTokenInterface
    {
        // Convert from Base64 URL-safe to normal Base64
        $base64 = strtr($token, '-_', '+/');

        // Decrypt the token
        $decrypted = $this->encrypter->decrypt(base64_decode($base64));

        // Parse the decrypted token
        [$name, $timestamp] = explode('|', $decrypted);

        return new ExpirableToken($token, $decrypted, Carbon::parse($timestamp));
    }

    /**
     * Get should expire in 2 days by default.
     *
     * @return int
     */
    public function getExpiresIn(): int
    {
        return $this->expiresIn;
    }

    /**
     * Set should expire in 2 days by default.
     *
     * @param int $expiresIn
     *
     * @return self
     */
    public function setExpiresIn(int $expiresIn): self
    {
        $this->expiresIn = $expiresIn;

        return $this;
    }
}
