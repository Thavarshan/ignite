<?php

namespace Ignite\Auth\Providers;

use Ignite\Auth\Actions\AuthenticateUser;
use Ignite\Auth\Actions\ResetUserPassword;
use Ignite\Auth\Actions\UnauthenticateUser;
use Ignite\Auth\Actions\UpdateRolePermissions;
use Ignite\Auth\Actions\ValidateIpAuthorisation;
use Ignite\Auth\Enums\Role as Type;
use Ignite\Auth\Facades\Auth;
use Ignite\Auth\Filters\PermissionFilter;
use Ignite\Auth\Filters\RoleFilter;
use Ignite\Auth\Http\Requests\AuthenticationRequest;
use Ignite\Auth\Http\Responses\AccountLockedResponse;
use Ignite\Auth\Http\Responses\AuthenticatedResponse;
use Ignite\Auth\Http\Responses\IpNotAuthorisedResponse;
use Ignite\Auth\Http\Responses\PermissionResponse;
use Ignite\Auth\Http\Responses\PermissionsCollectionResponse;
use Ignite\Auth\Http\Responses\RoleResponse;
use Ignite\Auth\Http\Responses\RolesCollectionResponse;
use Ignite\Auth\Http\Responses\UnauthenticatedResponse;
use Ignite\Auth\Models\Ip;
use Ignite\Auth\Models\Permission;
use Ignite\Auth\Models\Role;
use Ignite\Auth\Policies\PermissionPolicy;
use Ignite\Auth\Policies\RolePolicy;
use Ignite\Auth\Repositories\IpRepository;
use Ignite\Auth\Repositories\PermissionRepository;
use Ignite\Auth\Repositories\RoleRepository;
use Ignite\Auth\Services\ExpirableTokenService;
use Ignite\Auth\Services\IpService;
use Ignite\Auth\Services\PermissionService;
use Ignite\Auth\Services\RoleService;
use Ignite\Categories\Models\Category;
use Ignite\Categories\Policies\CategoryPolicy;
use Ignite\Discussions\Models\Discussion;
use Ignite\Discussions\Models\Reply;
use Ignite\Discussions\Policies\DiscussionPolicy;
use Ignite\Discussions\Policies\ReplyPolicy;
use Ignite\Instructions\Models\Instruction;
use Ignite\Instructions\Policies\InstructionPolicy;
use Ignite\Interfaces\Auth\AccountLockedResponse as AccountLockedResponseInterface;
use Ignite\Interfaces\Auth\AuthenticatedResponse as AuthenticatedResponseInterface;
use Ignite\Interfaces\Auth\AuthenticatesUsers;
use Ignite\Interfaces\Auth\AuthenticationRequest as AuthenticationRequestInterface;
use Ignite\Interfaces\Auth\ExpirableTokenService as ExpirableTokenServiceInterface;
use Ignite\Interfaces\Auth\Ip as IpInterface;
use Ignite\Interfaces\Auth\IpNotAuthorisedResponse as IpNotAuthorisedResponseInterface;
use Ignite\Interfaces\Auth\IpRepository as IpRepositoryInterface;
use Ignite\Interfaces\Auth\IpService as IpServiceInterface;
use Ignite\Interfaces\Auth\Permission as PermissionInterface;
use Ignite\Interfaces\Auth\PermissionFilter as PermissionFilterInterface;
use Ignite\Interfaces\Auth\PermissionRepository as PermissionRepositoryInterface;
use Ignite\Interfaces\Auth\PermissionResponse as PermissionResponseInterface;
use Ignite\Interfaces\Auth\PermissionsCollectionResponse as PermissionsCollectionResponseInterface;
use Ignite\Interfaces\Auth\PermissionService as PermissionServiceInterface;
use Ignite\Interfaces\Auth\ResetsUserPasswords;
use Ignite\Interfaces\Auth\Role as RoleInterface;
use Ignite\Interfaces\Auth\RoleFilter as RoleFilterInterface;
use Ignite\Interfaces\Auth\RoleRepository as RoleRepositoryInterface;
use Ignite\Interfaces\Auth\RoleResponse as RoleResponseInterface;
use Ignite\Interfaces\Auth\RolesCollectionResponse as RolesCollectionResponseInterface;
use Ignite\Interfaces\Auth\RoleService as RoleServiceInterface;
use Ignite\Interfaces\Auth\UnauthenticatedResponse as UnauthenticatedResponseInterface;
use Ignite\Interfaces\Auth\UnauthenticatesUsers;
use Ignite\Interfaces\Auth\UpdatesRolePermissions;
use Ignite\Interfaces\Auth\User as AuthUser;
use Ignite\Interfaces\Auth\ValidatesIpAuthorisation;
use Ignite\Interfaces\Users\CreatesNewUsers;
use Ignite\Interfaces\Users\UpdatesUserInformation;
use Ignite\Interfaces\Users\UpdatesUserPasswords;
use Ignite\Interfaces\Users\User as UserInterface;
use Ignite\Locations\Models\Location;
use Ignite\Locations\Policies\LocationPolicy;
use Ignite\Materials\Policies\MaterialPolicy;
use Ignite\Notes\Models\Note;
use Ignite\Notes\Policies\NotePolicy;
use Ignite\Organisations\Models\Invitation;
use Ignite\Organisations\Models\Organisation;
use Ignite\Organisations\Models\Team;
use Ignite\Organisations\Policies\InvitationPolicy;
use Ignite\Organisations\Policies\OrganisationPolicy;
use Ignite\Organisations\Policies\TeamPolicy;
use Ignite\Police\Models\Group;
use Ignite\Police\Models\Police;
use Ignite\Police\Models\Question;
use Ignite\Police\Models\Report;
use Ignite\Police\Policies\GroupPolicy;
use Ignite\Police\Policies\PolicePolicy;
use Ignite\Police\Policies\QuestionPolicy;
use Ignite\Police\Policies\ReportPolicy;
use Ignite\Tasks\Models\Task;
use Ignite\Tasks\Policies\TaskPolicy;
use Ignite\Users\Actions\CreateNewUser;
use Ignite\Users\Actions\UpdateUserInformation;
use Ignite\Users\Actions\UpdateUserPassword;
use Ignite\Users\Models\User;
use Ignite\Users\Policies\UserPolicy;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;
use Laravel\Jetstream\Jetstream;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Permission::class => PermissionPolicy::class,
        Role::class => RolePolicy::class,
        Team::class => TeamPolicy::class,
        Organisation::class => OrganisationPolicy::class,
        User::class => UserPolicy::class,
        Invitation::class => InvitationPolicy::class,
        Location::class => LocationPolicy::class,
        Police::class => PolicePolicy::class,
        Category::class => CategoryPolicy::class,
        Question::class => QuestionPolicy::class,
        Group::class => GroupPolicy::class,
        Report::class => ReportPolicy::class,
        Instruction::class => InstructionPolicy::class,
        Discussion::class => DiscussionPolicy::class,
        Reply::class => ReplyPolicy::class,
        Media::class => MaterialPolicy::class,
        Task::class => TaskPolicy::class,
        Note::class => NotePolicy::class,
        // Price::class => PricePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerCustomHttpRequestObjects();
        $this->registerRateLimiters();

        $this->registerApiTokenPermissions();
        $this->grantAdminAccessToAllActions();

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
        $this->app->singleton(RoleFilterInterface::class, RoleFilter::class);
        $this->app->singleton(PermissionFilterInterface::class, PermissionFilter::class);
    }

    /**
     * Register the rate limiters for the application.
     *
     * @return void
     */
    protected function registerRateLimiters(): void
    {
        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(
                Str::lower($request->input(
                    key: Auth::username('email')
                )) . '|' . $request->ip() // phpcs:ignore
            );

            return Limit::perMinute(
                ! app()->isProduction() ? 100 : 5
            )->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
    }

    /**
     * Register all repository classes.
     *
     * @return void
     */
    protected function registerRepositories(): void
    {
        $this->app->singleton(IpRepositoryInterface::class, IpRepository::class);
        $this->app->singleton(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->singleton(PermissionRepositoryInterface::class, PermissionRepository::class);
    }

    /**
     * Register all service classes.
     *
     * @return void
     */
    protected function registerServices(): void
    {
        $this->app->singleton(ExpirableTokenServiceInterface::class, function ($app) {
            $service = new ExpirableTokenService($app->make('encrypter'));

            $service->setExpiresIn(
                $app['config']->get('auth.tokens.expires_in', 3)
            );

            return $service;
        });

        $this->app->singleton(IpServiceInterface::class, function ($app) {
            return new IpService($app->make(IpRepositoryInterface::class));
        });

        $this->app->singleton(RoleServiceInterface::class, function ($app) {
            return new RoleService($app->make(RoleRepositoryInterface::class));
        });

        $this->app->singleton(PermissionServiceInterface::class, function ($app) {
            return new PermissionService(
                $app->make(PermissionRepositoryInterface::class)
            );
        });
    }

    /**
     * Register the actions for the application.
     *
     * @return void
     */
    protected function registerActions(): void
    {
        $this->app->singleton(AuthenticatesUsers::class, AuthenticateUser::class);
        $this->app->singleton(UnauthenticatesUsers::class, UnauthenticateUser::class);

        Fortify::createUsersUsing($abstract = CreateNewUser::class);
        $this->app->singleton(CreatesNewUsers::class, $abstract);

        Fortify::updateUserProfileInformationUsing($abstract = UpdateUserInformation::class);
        $this->app->singleton(UpdatesUserInformation::class, $abstract);

        Fortify::updateUserPasswordsUsing($abstract = UpdateUserPassword::class);
        $this->app->singleton(UpdatesUserPasswords::class, $abstract);

        Fortify::resetUserPasswordsUsing($abstract = ResetUserPassword::class);
        $this->app->singleton(ResetsUserPasswords::class, $abstract);

        $this->app->singleton(ValidatesIpAuthorisation::class, ValidateIpAuthorisation::class);

        $this->app->singleton(UpdatesRolePermissions::class, UpdateRolePermissions::class);
    }

    /**
     * Register the custom HTTP request objects for the application.
     *
     * @return void
     */
    protected function registerCustomHttpRequestObjects(): void
    {
        $this->app->singleton(AuthenticationRequestInterface::class, function ($app) {
            return $app->make(AuthenticationRequest::class);
        });
    }

    /**
     * Register all response classes.
     *
     * @return void
     */
    protected function registerResponses(): void
    {
        $this->app->bind(AuthenticatedResponseInterface::class, AuthenticatedResponse::class);
        $this->app->bind(UnauthenticatedResponseInterface::class, UnauthenticatedResponse::class);
        $this->app->bind(AccountLockedResponseInterface::class, AccountLockedResponse::class);
        $this->app->bind(IpNotAuthorisedResponseInterface::class, IpNotAuthorisedResponse::class);

        $this->app->bind(RoleResponseInterface::class, RoleResponse::class);
        $this->app->bind(RolesCollectionResponseInterface::class, RolesCollectionResponse::class);

        $this->app->bind(PermissionResponseInterface::class, PermissionResponse::class);
        $this->app->bind(PermissionsCollectionResponseInterface::class, PermissionsCollectionResponse::class);
    }

    /**
     * Grant admin access to all policies.
     *
     * @return void
     */
    protected function grantAdminAccessToAllActions(): void
    {
        Gate::before(function (UserInterface $user) {
            if ($user->hasRole(Type::ADMIN->value)) {
                return true;
            }
        });
    }

    /**
     * Register all model classes.
     *
     * @return void
     */
    protected function registerModels(): void
    {
        $this->app->singleton(
            IpInterface::class,
            fn ($app) => $app->make(Ip::class)
        );

        $this->app->singleton(
            RoleInterface::class,
            fn ($app) => $app->make(Role::class)
        );

        $this->app->singleton(
            PermissionInterface::class,
            fn ($app) => $app->make(Permission::class)
        );
    }

    /**
     * Register resource resolution logic.
     *
     * @return void
     */
    protected function registerResolutionLogic(): void
    {
        $this->app->singleton(
            AuthUser::class,
            fn ($app) => $app['request']->user() ?? new User()
        );

        Route::bind('ip', function (string $value) {
            return $this->app->make(
                IpServiceInterface::class
            )->find($value);
        });

        Route::bind('role', function (string $value) {
            return $this->app->make(
                RoleServiceInterface::class
            )->find($value);
        });

        Route::bind('permission', function (string $value) {
            return $this->app->make(
                PermissionServiceInterface::class
            )->find($value);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    protected function registerApiTokenPermissions(): void
    {
        Jetstream::defaultApiTokenPermissions(['read']);
    }
}
