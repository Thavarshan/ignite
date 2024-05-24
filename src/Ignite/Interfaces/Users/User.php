<?php

namespace Ignite\Interfaces\Users;

use Laravel\Sanctum\Contracts\HasApiTokens;
use Ignite\Interfaces\Activities\Trackable;
use Ignite\Interfaces\Auth\Authenticatable;
use Ignite\Interfaces\Auth\Authorisable;
use Ignite\Interfaces\Locations\Locatable;
use Ignite\Interfaces\Materials\HasMedia;
use Ignite\Interfaces\Organisations\BelongsToOrganisation;
use Ignite\Interfaces\Organisations\CanBeInvited;
use Ignite\Interfaces\Organisations\HasTeams;
use Ignite\Interfaces\Search\Searchable;
use Ignite\Interfaces\Settings\HasSettings;
use Ignite\Interfaces\Shared\Entity;
use Ignite\Interfaces\Shared\Filterable;
use Ignite\Interfaces\Shared\Hashable;
use Ignite\Interfaces\Shared\HasName;
use Ignite\Interfaces\Shared\Lockable;
use Ignite\Interfaces\Shared\Notifiable;

// phpcs:disable
/**
 * @property string                                                                           $name
 * @property string                                                                           $email
 * @property string                                                                           $firstname
 * @property string                                                                           $lastname
 * @property string|null                                                                      $middlenames
 * @property string|null                                                                      $media_url
 * @property string|null                                                                      $two_factor_secret
 * @property string|null                                                                      $two_factor_recovery_codes
 * @property int|null                                                                         $current_team_id
 * @property int|null                                                                         $organisation_id
 * @property string|null                                                                      $remember_token
 * @property string|null                                                                      $password
 * @property string|null                                                                      $uid
 * @property \Illuminate\Support\Carbon|null                                                  $locked_at
 * @property \Ignite\Interfaces\Auth\Ip                                                     $ip
 * @property \Illuminate\Support\Carbon|null                                                  $email_verified_at
 * @property \Illuminate\Support\Carbon|null                                                  $created_at
 * @property \Illuminate\Support\Carbon|null                                                  $updated_at
 * @property \Ignite\Interfaces\Organisations\Organisation                                  $organisation
 * @property \Ignite\Interfaces\Settings\Settings                                           $settings
 * @property \Ignite\Interfaces\Locations\Location|null                                     $location
 * @property \Illuminate\Database\Eloquent\Collection|\Ignite\Interfaces\Organisations\Team $teams
 * @property \Ignite\Interfaces\Organisations\Team                                          $currentTeam
 * @property \Laravel\Sanctum\PersonalAccessToken                                             $tokens
 * @property \Illuminate\Database\Eloquent\Collection|array<\Ignite\Users\Models\Session>   $sessions
 */
// phpcs:enable
interface User extends Authenticatable, Authorisable, Entity, HasName, Hashable, BelongsToOrganisation, HasTeams, Lockable, HasMedia, Filterable, HasApiTokens, CanBeInvited, HasSettings, Notifiable, Trackable, Locatable, Searchable // phpcs:ignore
{
    /**
     * The email of this person.
     *
     * @return string
     */
    public function getEmail(): string;
}
