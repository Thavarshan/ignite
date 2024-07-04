<?php

namespace Ignite\Auth\Models\Traits;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Ignite\Interfaces\Auth\ExpirableToken as ExpirableTokenInterface;
use Ignite\Interfaces\Auth\ExpirableTokenService;
use Ignite\Interfaces\Auth\HasExpiringToken as HasExpiringTokenInterface;
use Illuminate\Database\Eloquent\Model;

trait HasExpiringToken
{
    /**
     * The expiration timestamp.
     *
     * @var \Carbon\CarbonInterface|null
     */
    protected ?CarbonInterface $expiredAt;

    /**
     * Boot the trait.
     *
     * @return void
     */
    public static function bootHasExpiringToken(): void
    {
        static::creating(function (HasExpiringTokenInterface|Model $model) {
            if (! has_attribute($model, 'token')) {
                return;
            }

            $model->token = self::tokenService() // @phpstan-ignore-line
                ->encrypt($model->getMorphClass())
                ->getValue();
        });
    }

    /**
     * Get the token instance.
     *
     * @return \Ignite\Interfaces\Auth\ExpirableToken
     */
    public function getToken(): ExpirableTokenInterface
    {
        if (is_string($this->token)) {
            $this->token = self::tokenService()->decrypt($this->token);
        }

        return $this->token;
    }

    /**
     * Get the expiration timestamp.
     *
     * @return \Carbon\CarbonInterface
     */
    public function getExpiredAt(): CarbonInterface
    {
        if (is_null($this->token)) {
            return $this->expiredAt ?? Carbon::now()->addDays(2);
        }

        return $this->getToken()->getExpiredAt();
    }

    /**
     * Set the expiration timestamp.
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
     * Determine if the entity has expired.
     *
     * @return bool
     */
    public function isExpired(): bool
    {
        return $this->getExpiredAt()->isPast();
    }

    /**
     * Get the token service instance.
     *
     * @return \Ignite\Interfaces\Auth\ExpirableTokenService
     */
    protected static function tokenService(): ExpirableTokenService
    {
        return app(ExpirableTokenService::class);
    }
}
