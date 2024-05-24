<?php

namespace Ignite\Users\Actions;

use Ignite\Auth\Enums\Role;
use Ignite\Interfaces\Auth\RoleService;
use Ignite\Interfaces\Materials\HasMedia;
use Ignite\Interfaces\Organisations\Invitation;
use Ignite\Interfaces\Organisations\InvitationService;
use Ignite\Interfaces\Organisations\Organisation;
use Ignite\Interfaces\Organisations\OrganisationService;
use Ignite\Interfaces\Shared\Entity;
use Ignite\Interfaces\Users\CreatesNewUsers;
use Ignite\Interfaces\Users\User;
use Ignite\Organisations\Exceptions\InvitationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Throwable;

class CreateNewUser implements CreatesNewUsers
{
    /**
     * The default user role.
     *
     * @var \Illuminate\Database\Eloquent\Model|\Ignite\Interfaces\Shared\Entity|null
     */
    protected Model|Entity|null $defaultRole = null;

    /**
     * The newly created user instance.
     *
     * @var \Ignite\Interfaces\Users\User
     */
    protected User $user;

    /**
     * Create new CreateNewUser instance.
     *
     * @param \Ignite\Interfaces\Organisations\OrganisationService $organisations
     * @param \Ignite\Interfaces\Organisations\InvitationService   $invitations
     * @param \Ignite\Interfaces\Auth\RoleService                  $roles
     *
     * @return void
     */
    public function __construct(
        protected readonly OrganisationService $organisations,
        protected readonly InvitationService $invitations,
        protected readonly RoleService $roles
    ) {
    }

    /**
     * Create a newly registered user.
     *
     * @param array<string, mixed>      $data
     * @param array<string, mixed>|null $options
     *
     * @return \Ignite\Interfaces\Users\User
     */
    public function create(array $data, ?array $options = []): User
    {
        return transform(
            $this->findOrganisation($data, $options),
            function (Organisation $organisation) use ($data) {
                /** @var \Ignite\Interfaces\Users\User $user */
                $user = DB::transaction(fn () => $organisation->createUser($data, [
                    'role' => $this->getDefaultUserRole(),
                ]));

                $user->assignRole($data['role'] ?? $this->getDefaultUserRole());

                $user->applyDefaultSettings();

                if (isset($data['image']) && $user instanceof HasMedia) {
                    $user->updateMediaItem($data['image']);
                }

                $this->user = $user;

                return $user;
            }
        );
    }

    /**
     * Get the default user role.
     *
     * @return \Illuminate\Database\Eloquent\Model|\Ignite\Interfaces\Shared\Entity
     */
    public function getDefaultUserRole(): Model|Entity
    {
        if (is_null($this->defaultRole)) {
            $this->defaultRole = $this->roles->findBy(
                'name',
                Role::CLIENT_STAFF->value
            );
        }

        return $this->defaultRole;
    }

    /**
     * Set the default user role.
     *
     * @param \Illuminate\Database\Eloquent\Model|\Ignite\Interfaces\Shared\Entity|string $role
     *
     * @return void
     */
    public function setDefaultUserRole(Model|Entity|string $role): void
    {
        if (is_string($role)) {
            $role = $this->roles->findBy('name', $role);
        }

        $this->defaultRole = $role;
    }

    /**
     * Get the newly created user instance.
     *
     * @return \Ignite\Interfaces\Users\User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * Find the organisation.
     *
     * @param array<string, mixed>      $data
     * @param array<string, mixed>|null $options
     *
     * @return \Illuminate\Database\Eloquent\Model|\Ignite\Interfaces\Shared\Entity
     */
    protected function findOrganisation(array $data, ?array $options = []): Model|Entity
    {
        try {
            return $this->organisations->find(
                Arr::get($data, 'organisation_id', $options['organisation_id'] ?? null)
            );
        } catch (Throwable $e) {
            return tap($this->invitations->findBy( // @phpstan-ignore-line
                'email',
                $data['email']
            ), function (Invitation $invitation) {
                if (! $invitation->accepted()) {
                    throw InvitationException::notAccepted($invitation);
                }

                $this->setDefaultUserRole($invitation->getRole());
            })->getOrganisation();
        }
    }
}
