<?php

namespace Ignite\Users\Actions;

use Ignite\Interfaces\Users\UpdatesUserInformation;
use Ignite\Interfaces\Users\User;
use Ignite\Interfaces\Users\UserService;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class UpdateUserInformation implements UpdatesUserInformation
{
    /**
     * Create a new controller instance.
     *
     * @param \Ignite\Interfaces\Users\UserService $service
     *
     * @return void
     */
    public function __construct(protected UserService $service)
    {
    }

    /**
     * Update the given user's profile information.
     *
     * @param \Ignite\Interfaces\Users\User $user
     * @param array<string, mixed>          $data
     * @param array<string, mixed>|null     $options
     *
     * @return \Ignite\Interfaces\Users\User
     */
    public function update(User $user, array $data, ?array $options = []): User
    {
        return tap($user, function (User $user) use ($data, $options) {
            if (
                $data['email'] !== $user->getEmail()
                && $user instanceof MustVerifyEmail
            ) {
                $data['email_verified_at'] = null;
            }

            $this->service->update($user, $data, $options);

            if (
                isset($data['email_verified_at'])
                && method_exists($user, 'markEmailAsVerified')
            ) {
                call_user_func_array([$user, 'markEmailAsVerified'], []);
            }

            if (isset($data['image'])) {
                $user->updateMediaItem($data['image']);
            }
        })->fresh();
    }
}
