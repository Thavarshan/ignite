<?php

namespace Ignite\Notes\Providers;

use Ignite\Interfaces\Notes\Note as NoteInterface;
use Ignite\Interfaces\Notes\NoteRepository as NoteRepositoryInterface;
use Ignite\Interfaces\Notes\NoteResponse as NoteResponseInterface;
use Ignite\Interfaces\Notes\NotesCollectionResponse as NotesCollectionResponseInterface;
use Ignite\Interfaces\Notes\NoteService as NoteServiceInterface;
use Ignite\Interfaces\Notes\NotifiesNoteRelatedParties;
use Ignite\Notes\Actions\NotifyNoteRelatedParties;
use Ignite\Notes\Http\Responses\NoteResponse;
use Ignite\Notes\Http\Responses\NotesCollectionResponse;
use Ignite\Notes\Models\Note;
use Ignite\Notes\Repositories\NoteRepository;
use Ignite\Notes\Services\NoteService;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class NotesServiceProvider extends ServiceProvider
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
    public function registerRepositories(): void
    {
        $this->app->singleton(
            NoteRepositoryInterface::class,
            NoteRepository::class
        );
    }

    /**
     * Register all service classes.
     *
     * @return void
     */
    public function registerServices(): void
    {
        $this->app->singleton(NoteServiceInterface::class, function ($app) {
            return new NoteService($app->make(NoteRepositoryInterface::class));
        });
    }

    /**
     * Register all response classes.
     *
     * @return void
     */
    public function registerResponses(): void
    {
        $this->app->bind(NoteResponseInterface::class, NoteResponse::class);
        $this->app->bind(
            NotesCollectionResponseInterface::class,
            NotesCollectionResponse::class
        );
    }

    /**
     * Register all action classes.
     *
     * @return void
     */
    protected function registerActions(): void
    {
        $this->app->singleton(NotifiesNoteRelatedParties::class, NotifyNoteRelatedParties::class);
    }

    /**
     * Register all model classes.
     *
     * @return void
     */
    protected function registerModels(): void
    {
        $this->app->singleton(
            NoteInterface::class,
            fn ($app) => $app->make(Note::class)
        );
    }

    /**
     * Register resource resolution logic.
     *
     * @return void
     */
    protected function registerResolutionLogic(): void
    {
        Route::bind('note', function (string $value) {
            return $this->app->make(
                NoteServiceInterface::class
            )->find($value);
        });
    }
}
