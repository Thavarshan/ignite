<?php

namespace Ignite\Tasks\Providers;

use Ignite\Auth\Enums\Role as Type;
use Ignite\Interfaces\Tasks\CreatesNewTasks;
use Ignite\Interfaces\Tasks\DeletesTasks;
use Ignite\Interfaces\Tasks\Task as TaskInterface;
use Ignite\Interfaces\Tasks\TaskFilter as TaskFilterInterface;
use Ignite\Interfaces\Tasks\TaskRepository as TaskRepositoryInterface;
use Ignite\Interfaces\Tasks\TaskResponse;
use Ignite\Interfaces\Tasks\TaskResponse as TaskResponseInterface;
use Ignite\Interfaces\Tasks\TasksCollectionResponse;
use Ignite\Interfaces\Tasks\TasksCollectionResponse as TasksCollectionResponseInterface;
use Ignite\Interfaces\Tasks\TaskService as TaskServiceInterface;
use Ignite\Interfaces\Tasks\UpdatesTaskInformation;
use Ignite\Tasks\Actions\CreateNewTask;
use Ignite\Tasks\Actions\DeleteTask;
use Ignite\Tasks\Actions\UpdateTaskInformation;
use Ignite\Tasks\Filters\TaskFilter;
use Ignite\Tasks\Models\Task;
use Ignite\Tasks\Repositories\TaskRepository;
use Ignite\Tasks\Services\TaskService;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class TasksServiceProvider extends ServiceProvider
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
        $this->app->singleton(TaskFilterInterface::class, TaskFilter::class);
    }

    /**
     * Register all repository classes.
     *
     * @return void
     */
    public function registerRepositories(): void
    {
        $this->app->singleton(
            TaskRepositoryInterface::class,
            TaskRepository::class
        );
    }

    /**
     * Register all service classes.
     *
     * @return void
     */
    public function registerServices(): void
    {
        $this->app->singleton(TaskServiceInterface::class, function ($app) {
            return new TaskService($app->make(TaskRepositoryInterface::class));
        });
    }

    /**
     * Register all response classes.
     *
     * @return void
     */
    public function registerResponses(): void
    {
        $this->app->bind(TaskResponseInterface::class, TaskResponse::class);
        $this->app->bind(
            TasksCollectionResponseInterface::class,
            TasksCollectionResponse::class
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
            TaskInterface::class,
            fn ($app) => $app->make(Task::class)
        );
    }

    /**
     * Register all action classes.
     *
     * @return void
     */
    protected function registerActions(): void
    {
        $this->app->singleton(CreatesNewTasks::class, function ($app) {
            $creator = $app->make(CreateNewTask::class);
            $creator->setDefaultTaskRole(Type::CLIENT_STAFF->value);

            return $creator;
        });

        $this->app->singleton(DeletesTasks::class, DeleteTask::class);
        $this->app->singleton(
            UpdatesTaskInformation::class,
            UpdateTaskInformation::class
        );
    }

    /**
     * Register resource resolution logic.
     *
     * @return void
     */
    protected function registerResolutionLogic(): void
    {
        Route::bind('task', function (string $value) {
            return $this->app->make(
                TaskServiceInterface::class
            )->find($value);
        });
    }
}
