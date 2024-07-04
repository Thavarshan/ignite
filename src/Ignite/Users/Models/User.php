<?php

namespace Ignite\Users\Models;

use Ignite\Activities\Models\Traits\Trackable;
use Ignite\Auth\Models\Traits\Authenticatable as AuthenticatableTrait;
use Ignite\Auth\Models\Traits\HasRole;
use Ignite\Interfaces\Organisations\Invitation;
use Ignite\Interfaces\Organisations\Organisation as OrganisationInterface;
use Ignite\Interfaces\Users\User as UserInterface;
use Ignite\Locations\Models\Traits\Locatable;
use Ignite\Materials\Traits\HasMedia;
use Ignite\Organisations\Models\Traits\BelongsToOrganisation;
use Ignite\Organisations\Models\Traits\HasTeams;
use Ignite\Organisations\Notifications\InvitedToOrganisationNotification;
use Ignite\Settings\Models\Traits\HasSettings;
use Ignite\Shared\Data\Traits\Entity;
use Ignite\Shared\Data\Traits\Filterable;
use Ignite\Shared\Traits\Hashable;
use Ignite\Shared\Traits\Lockable;
use Ignite\Shared\Traits\Personable;
use Ignite\Tasks\Models\Traits\HasTasks;
use Ignite\Users\Enums\User as UserEnum;
use Ignite\Users\Observers\UserObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Scout\Attributes\SearchUsingPrefix;
use Laravel\Scout\Searchable;
use OwenIt\Auditing\Auditable;

// phpcs:disable
/**
 * @property string                                                                         $name
 * @property string                                                                         $email
 * @property string                                                                         $firstname
 * @property string                                                                         $lastname
 * @property string|null                                                                    $middlenames
 * @property string|null                                                                    $media_url
 * @property string|null                                                                    $two_factor_secret
 * @property string|null                                                                    $two_factor_recovery_codes
 * @property int|null                                                                       $current_team_id
 * @property int|null                                                                       $organisation_id
 * @property string|null                                                                    $remember_token
 * @property string|null                                                                    $password
 * @property string|null                                                                    $uid
 * @property \Illuminate\Support\Carbon|null                                                $locked_at
 * @property \Ignite\Interfaces\Auth\Ip                                                     $ip
 * @property \Illuminate\Support\Carbon|null                                                $email_verified_at
 * @property \Illuminate\Support\Carbon|null                                                $created_at
 * @property \Illuminate\Support\Carbon|null                                                $updated_at
 * @property \Ignite\Interfaces\Organisations\Organisation                                  $organisation
 * @property \Ignite\Interfaces\Settings\Settings                                           $settings
 * @property \Ignite\Interfaces\Locations\Location|null                                     $location
 * @property \Illuminate\Database\Eloquent\Collection|\Ignite\Interfaces\Organisations\Team $teams
 * @property \Ignite\Interfaces\Organisations\Team                                          $currentTeam
 * @property \Laravel\Sanctum\PersonalAccessToken                                           $tokens
 * @property \Illuminate\Database\Eloquent\Collection|array<\Ignite\Users\Models\Session>   $sessions
 */
// phpcs:enable
#[ObservedBy(UserObserver::class)]
class User extends Authenticatable implements UserInterface
{
    use HasApiTokens;
    use HasFactory;
    use HasMedia;
    use BelongsToOrganisation;
    use HasTeams;
    use HasRole;
    use Notifiable;
    use Entity;
    use Personable;
    use Lockable;
    use Hashable;
    use HasSettings;
    use Filterable;
    use Trackable;
    use Locatable;
    use AuthenticatableTrait;
    use TwoFactorAuthenticatable;
    use Searchable;
    use HasTasks;
    use Auditable;

    /**
     * The prefix for the hash id.
     *
     * @var string
     */
    protected string $hashIdPrefix = 'NW-USER';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uid',
        'firstname',
        'lastname',
        'middlenames',
        'email',
        'phone',
        'password',
        'email_verified_at',
        'locked_at',
        'current_team_id',
        'organisation_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'locked_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'media_url',
        'name',
        'role',
    ];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return UserEnum::UID->value;
    }

    /**
     * Send an invitation notification to the user.
     *
     * @param \Ignite\Interfaces\Organisations\Organisation $organisation
     * @param \Ignite\Interfaces\Organisations\Invitation   $invitation
     *
     * @return void
     */
    public function sendInvitationNotification(
        OrganisationInterface $organisation,
        Invitation $invitation
    ): void {
        $this->notify(new InvitedToOrganisationNotification(
            organisation: $organisation,
            invitation: $invitation,
        ));
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array<string, mixed>
     */
    #[SearchUsingPrefix(['id', 'uid', 'email'])]
    public function toSearchableArray(): array
    {
        return [
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'middlenames' => $this->middlenames,
            'email' => $this->email,
        ];
    }
}
