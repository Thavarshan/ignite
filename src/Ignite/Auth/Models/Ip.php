<?php

namespace Ignite\Auth\Models;

use Carbon\Carbon;
use Ignite\Activities\Models\Traits\Trackable;
use Ignite\Interfaces\Auth\Ip as IpInterface;
use Ignite\Shared\Data\Traits\Entity;
use Ignite\Shared\Data\Traits\Filterable;
use Ignite\Users\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string                    $ip
 * @property int                       $user_id
 * @property \Carbon\Carbon|null       $authorised_at
 * @property \Ignite\Users\Models\User $user
 */
class Ip extends Model implements IpInterface
{
    use HasFactory;
    use Entity;
    use Filterable;
    use Trackable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ips';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'ip',
        'user_id',
        'authorised_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'authorised_at' => 'datetime',
    ];

    /**
     * Get the user that owns the ip.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if the ip address is authorised.
     *
     * @return bool
     */
    public function isAuthorised(): bool
    {
        return ! is_null($this->authorised_at);
    }

    /**
     * Authorise the ip address.
     *
     * @return void
     */
    public function authorise(): void
    {
        $this->authorised_at = Carbon::now();

        $this->save();
    }

    /**
     * Unauthorise the ip address.
     *
     * @return void
     */
    public function unauthorise(): void
    {
        $this->authorised_at = null;

        $this->save();
    }
}
