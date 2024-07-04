<?php

namespace Ignite\Tasks\Models;

use Ignite\Activities\Models\Traits\Trackable;
use Ignite\Interfaces\Tasks\Task as TaskInterface;
use Ignite\Interfaces\Users\User as UserInterface;
use Ignite\Materials\Traits\HasMedia;
use Ignite\Organisations\Models\Traits\BelongsToOrganisation;
use Ignite\Organisations\Models\Traits\HasTeams;
use Ignite\Shared\Data\Traits\Entity;
use Ignite\Shared\Data\Traits\Filterable;
use Ignite\Shared\Traits\HasName;
use Ignite\Shared\Traits\Sluggable;
use Ignite\Users\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Laravel\Scout\Searchable;
use OwenIt\Auditing\Auditable;

// phpcs:disable
/**
 * @property int                                                                                          $id
 * @property string                                                                                       $name
 * @property string                                                                                       $description
 * @property int                                                                                          $organisation_id
 * @property int                                                                                          $taskable_id
 * @property string                                                                                       $taskable_type
 * @property \Ignite\Organisations\Models\Organisation                                                    $organisation
 * @property \Illuminate\Database\Eloquent\Collection<\Ignite\Interfaces\Organisations\Team>              $teams
 * @property \Illuminate\Database\Eloquent\Collection<\Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property \Illuminate\Database\Eloquent\Collection<\Ignite\Users\Models\User>                          $users
 * @property \Illuminate\Database\Eloquent\Model                                                          $taskable
 * @property \Carbon\Carbon|null                                                                          $created_at
 * @property \Carbon\Carbon|null                                                                          $updated_at
 */
// phpcs:enable
class Task extends Model implements TaskInterface
{
    use HasFactory;
    use Entity;
    use HasName;
    use BelongsToOrganisation;
    use HasTeams;
    use HasMedia;
    use Filterable;
    use Trackable;
    use Searchable;
    use Sluggable;
    use Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'organisation_id',
        'taskable_id',
        'taskable_type',
    ];

    /**
     * Get the taskable model that this task belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function taskable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get all users this task is assigned to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'task_user')
            ->using(TaskUser::class);
    }

    /**
     * Assign this task to the given user.
     *
     * @param \Ignite\Interfaces\Users\User $user
     */
    public function assignTo(UserInterface $user): void
    {
        $this->users()->syncWithoutDetaching($user);
    }

    /**
     * Remove the given user from this task.
     *
     * @param \Ignite\Interfaces\Users\User $user
     */
    public function removeFrom(UserInterface $user): void
    {
        $this->users()->detach($user);
    }

    /**
     * Determine if this task is assigned to the given user.
     *
     * @param \Ignite\Interfaces\Users\User $user
     *
     * @return bool
     */
    public function isAssignedTo(UserInterface $user): bool
    {
        return $this->users->contains($user);
    }
}
