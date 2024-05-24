<?php

namespace Ignite\Users\Actions;

use App\Support\Loggable;
use Closure;
use Ignite\Interfaces\Users\DeletesUsers;
use Ignite\Interfaces\Users\User;
use Ignite\Interfaces\Users\UserService;
use Illuminate\Support\Arr;
use Throwable;

class DeleteUser implements DeletesUsers
{
    use Loggable;

    /**
     * The before delete callbacks.
     *
     * @var array
     */
    protected static array $beforeDeleteCallbacks = [];

    /**
     * Create a new action instance.
     *
     * @param \Ignite\Interfaces\Users\UserService $service
     *
     * @return void
     */
    public function __construct(protected readonly UserService $service)
    {
    }

    /**
     * Delete the given user.
     *
     * @param \Ignite\Interfaces\Users\User $user
     *
     * @return void
     */
    public function delete(User $user): void
    {
        $this->service->delete(tap($user, function (User $user): void {
            $user->deleteMediaItem();

            try {
                self::runDeletionCallbacks($user);
            } catch (Throwable $e) {
                $this->logError($e->getMessage(), [
                    'user' => $user->toArray(),
                    'exception' => $e,
                ]);
            }

            if (property_exists($user, 'tokens')) {
                $user->tokens->each->delete();
            }
        }));
    }

    /**
     * Get the user delete callbacks.
     *
     * @return array
     */
    public static function getBeforeDeleteCallbacks(): array
    {
        return array_filter(array_merge(
            self::$beforeDeleteCallbacks,
            config('users.callbacks.deleting', [])
        ));
    }

    /**
     * Register a before delete callback.
     *
     * @param Closure $callback
     *
     * @return void
     */
    public static function beforeDelete(Closure $callback): void
    {
        self::$beforeDeleteCallbacks[] = $callback;
    }

    /**
     * Run the user delete callbacks.
     *
     * @param \Ignite\Interfaces\Users\User $user
     *
     * @return void
     */
    protected static function runDeletionCallbacks(User $user): void
    {
        collect(Arr::wrap(self::getBeforeDeleteCallbacks()))
            ->each(fn (Closure $callback): mixed => $callback($user));
    }
}
