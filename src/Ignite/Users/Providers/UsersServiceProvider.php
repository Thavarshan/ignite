<?php

namespace Ignite\Users\Providers;

use Ignite\Auth\Enums\Role as Type;
use Ignite\Interfaces\Users\CreatesNewUsers;
use Ignite\Interfaces\Users\DeletesUsers;
use Ignite\Interfaces\Users\UpdatesUserInformation;
use Ignite\Interfaces\Users\UpdatesUserPasswords;
use Ignite\Interfaces\Users\User as UserInterface;
use Ignite\Interfaces\Users\UserFilter as UserFilterInterface;
use Ignite\Interfaces\Users\UserRepository as UserRepositoryInterface;
use Ignite\Interfaces\Users\UserResponse as UserResponseInterface;
use Ignite\Interfaces\Users\UsersCollectionResponse as UsersCollectionResponseInterface;
use Ignite\Interfaces\Users\UserService as UserServiceInterface;
use Ignite\Interfaces\Users\UserViewResponse as UserViewResponseInterface;
use Ignite\Users\Actions\CreateNewUser;
use Ignite\Users\Actions\DeleteUser;
use Ignite\Users\Actions\UpdateUserInformation;
use Ignite\Users\Actions\UpdateUserPassword;
use Ignite\Users\Filters\UserFilter;
use Ignite\Users\Http\Responses\UserResponse;
use Ignite\Users\Http\Responses\UsersCollectionResponse;
use Ignite\Users\Http\Responses\UserViewResponse;
use Ignite\Users\Models\User;
use Ignite\Users\Repositories\UserRepository;
use Ignite\Users\Services\UserService;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class UsersServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerModels();
        $this->registerFilters();
        $this->registerRepositories();
        $this->registerServices();
        $this->registerActions();
        $this->registerResponses();

        $this->registerResolutionLogic();
    }

    /**
     * Register all repository classes.
     *
     * @return void
     */
    public function registerFilters(): void
    {
        $this->app->singleton(UserFilterInterface::class, UserFilter::class);
        // $this->app->singleton(NotificationFilterInterface::class, NotificationFilter::class);
    }

    /**
     * Register all repository classes.
     *
     * @return void
     */
    public function registerRepositories(): void
    {
        $this->app->singleton(
            UserRepositoryInterface::class,
            UserRepository::class
        );
    }

    /**
     * Register all service classes.
     *
     * @return void
     */
    public function registerServices(): void
    {
        $this->app->singleton(UserServiceInterface::class, function ($app) {
            return new UserService($app->make(UserRepositoryInterface::class));
        });
    }

    /**
     * Register all response classes.
     *
     * @return void
     */
    public function registerResponses(): void
    {
        $this->app->bind(UserResponseInterface::class, UserResponse::class);
        $this->app->bind(UserViewResponseInterface::class, UserViewResponse::class);
        $this->app->bind(
            UsersCollectionResponseInterface::class,
            UsersCollectionResponse::class
        );
    }

    /**
     * Register all model classes.
     *
     * @return void
     */
    protected function registerModels(): void
    {
        $this->app->singleton(
            UserInterface::class,
            fn ($app) => $app->make(User::class)
        );
    }

    /**
     * Register all action classes.
     *
     * @return void
     */
    protected function registerActions(): void
    {
        $this->app->singleton(CreatesNewUsers::class, function ($app) {
            $creator = $app->make(CreateNewUser::class);
            $creator->setDefaultUserRole(Type::CLIENT_STAFF->value);

            return $creator;
        });

        $this->app->singleton(DeletesUsers::class, function ($app) {
            DeleteUser::beforeDelete(function (User $user) {
                $user->removeFromTeams();
            });

            return $app->make(DeleteUser::class);
        });

        $this->app->singleton(
            UpdatesUserInformation::class,
            UpdateUserInformation::class
        );

        $this->app->singleton(
            UpdatesUserPasswords::class,
            UpdateUserPassword::class
        );
    }

    /**
     * Register resource resolution logic.
     *
     * @return void
     */
    protected function registerResolutionLogic(): void
    {
        Route::bind('user', function (string $value) {
            return $this->app->make(
                UserServiceInterface::class
            )->find($value);
        });
    }
}
