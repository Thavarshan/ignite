<?php

namespace Ignite\Discussions\Models\Traits;

use Ignite\Discussions\Models\Discussion;
use Ignite\Interfaces\Organisations\Team;
use Ignite\Interfaces\Users\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Discussable
{
    /**
     * Get the discussions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function discussions(): MorphMany
    {
        return $this->morphMany(Discussion::class, 'discussable');
    }

    /**
     * Get all discussion threads associated with this model.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getDiscussions(): Collection
    {
        return $this->discussions()->get();
    }

    /**
     * Create a discussion thread for this model.
     *
     * @param \Ignite\Interfaces\Organisations\Team|int $team
     *
     * @return \Ignite\Discussions\Models\Discussion
     */
    public function createDiscussionForTeam(Team|int $team): Discussion
    {
        return $this->discussions()->create([
            'title' => $this->client_reference,
            'body' => $this->description,
            'team_id' => $this->getTeamId($team),
            'organisation_id' => $this->getOrganisationId(),
        ]);
    }

    /**
     * Swicth discussion thread to another team.
     *
     * @param \Ignite\Interfaces\Organisations\Team|int $team
     */
    public function switchDiscussionTo(
        Discussion $discussion,
        Team|int $team
    ): void {
        $discussion->update([
            'team_id' => $this->getTeamId($team),
        ]);
    }

    /**
     * Determine this model involves the given user.
     *
     * @param \Ignite\Interfaces\Users\User $user
     *
     * @return bool
     */
    public function involves(User $user): bool
    {
        if (is_null($user->currentTeam)) {
            return false;
        }

        return $this->belongsToAssociation($user->currentTeam);
    }
}
