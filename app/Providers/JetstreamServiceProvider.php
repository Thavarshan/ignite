<?php

namespace App\Providers;

use App\Actions\Jetstream\AddTeamMember;
use App\Actions\Jetstream\CreateTeam;
use App\Actions\Jetstream\DeleteTeam;
use App\Actions\Jetstream\DeleteUser;
use App\Actions\Jetstream\InviteTeamMember;
use App\Actions\Jetstream\RemoveTeamMember;
use App\Actions\Jetstream\UpdateTeamName;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use Laravel\Fortify\Fortify;
use Laravel\Jetstream\Jetstream;

class JetstreamServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->bootInertia();

        $this->configureRoutes();
        $this->configurePermissions();

        Jetstream::createTeamsUsing(CreateTeam::class);
        Jetstream::updateTeamNamesUsing(UpdateTeamName::class);
        Jetstream::addTeamMembersUsing(AddTeamMember::class);
        Jetstream::inviteTeamMembersUsing(InviteTeamMember::class);
        Jetstream::removeTeamMembersUsing(RemoveTeamMember::class);
        Jetstream::deleteTeamsUsing(DeleteTeam::class);
        Jetstream::deleteUsersUsing(DeleteUser::class);
    }

    /**
     * Configure the routes offered by Jetstream.
     */
    protected function configureRoutes(): void
    {
        Route::group([
            'namespace' => 'Laravel\Jetstream\Http\Controllers',
            'domain' => config('jetstream.domain', null),
            'prefix' => config('jetstream.prefix', config('jetstream.path')),
        ], function () {
            $this->loadRoutesFrom(base_path('routes/jetstream.php'));
        });
    }

    /**
     * Configure the roles and permissions that are available within the application.
     */
    protected function configurePermissions(): void
    {
        Jetstream::defaultApiTokenPermissions(['read']);

        Jetstream::role('admin', 'Administrator', [
            'create',
            'read',
            'update',
            'delete',
        ])->description('Administrator users can perform any action.');

        Jetstream::role('editor', 'Editor', [
            'read',
            'create',
            'update',
        ])->description('Editor users have the ability to read, create, and update.');
    }

    /**
     * Boot any Inertia related services.
     *
     * @return void
     */
    protected function bootInertia()
    {
        Fortify::loginView(function () {
            return Inertia::render('auth/login', [
                'canResetPassword' => Route::has('password.request'),
                'status' => session('status'),
            ]);
        });

        Fortify::requestPasswordResetLinkView(function () {
            return Inertia::render('auth/forgot-password', [
                'status' => session('status'),
            ]);
        });

        Fortify::resetPasswordView(function (Request $request) {
            return Inertia::render('auth/reset-password', [
                'email' => $request->input('email'),
                'token' => $request->route('token'),
            ]);
        });

        Fortify::registerView(function () {
            return Inertia::render('auth/register');
        });

        Fortify::verifyEmailView(function () {
            return Inertia::render('auth/verify-email', [
                'status' => session('status'),
            ]);
        });

        Fortify::twoFactorChallengeView(function () {
            return Inertia::render('auth/two-factor-challenge');
        });

        Fortify::confirmPasswordView(function () {
            return Inertia::render('auth/confirm-password');
        });
    }
}
