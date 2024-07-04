<?php

namespace Ignite\Auth\Tokens;

use Carbon\CarbonInterface;
use Ignite\Interfaces\Auth\ExpirableToken as ExpirableTokenInterface;

class ExpirableToken implements ExpirableTokenInterface
{
    /**
     * Create a new token instance.
     *
     * @param string                  $value
     * @param string                  $plainTextValue
     * @param \Carbon\CarbonInterface $expiredAt
     *
     * @return void
     */
    public function __construct(
        protected string $value,
        protected string $plainTextValue,
        protected CarbonInterface $expiredAt
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return $this->getValue();
    }

    /**
     * Get the expiration timestamp of the token.
     *
     * @return \Carbon\CarbonInterface
     */
    public function getExpiredAt(): CarbonInterface
    {
        return $this->expiredAt;
    }

    /**
     * Set the expiration timestamp of the token.
     *
     * @param \Carbon\CarbonInterface $expiredAt
     *
     * @return void
     */
    public function setExpiredAt(CarbonInterface $expiredAt): void
    {
        $this->expiredAt = $expiredAt;
    }

    /**
     * Determine if the token has expired.
     *
     * @return bool
     */
    public function isExpired(): bool
    {
        return $this->getExpiredAt()->isPast();
    }

    /**
     * Get the plain text token value.
     *
     * @return string
     */
    public function getPlainTextValue(): string
    {
        return $this->plainTextValue;
    }

    /**
     * Set the plain text token value.
     *
     * @param string $value
     *
     * @return self
     */
    public function setPlainTextValue(string $value): self
    {
        $this->plainTextValue = $value;

        return $this;
    }

    /**
     * Get the token value.
     *
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Set the token value.
     *
     * @param string $value
     *
     * @return self
     */
    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }
}
