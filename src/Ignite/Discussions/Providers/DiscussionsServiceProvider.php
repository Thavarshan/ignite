<?php

namespace Ignite\Discussions\Providers;

use Ignite\Discussions\Actions\DeleteReply;
use Ignite\Discussions\Actions\UpdateReply;
use Ignite\Discussions\Filters\DiscussionFilter;
use Ignite\Discussions\Filters\ReplyFilter;
use Ignite\Discussions\Http\Responses\DiscussionResponse;
use Ignite\Discussions\Http\Responses\RepliesCollectionResponse;
use Ignite\Discussions\Http\Responses\ReplyResponse;
use Ignite\Discussions\Models\Discussion;
use Ignite\Discussions\Models\Reply;
use Ignite\Discussions\Repositories\DiscussionRepository;
use Ignite\Discussions\Repositories\ReplyRepository;
use Ignite\Discussions\Services\DiscussionService;
use Ignite\Discussions\Services\ReplyService;
use Ignite\Interfaces\Discussions\DeletesReplies;
use Ignite\Interfaces\Discussions\Discussion as DiscussionInterface;
use Ignite\Interfaces\Discussions\DiscussionFilter as DiscussionFilterInterface;
use Ignite\Interfaces\Discussions\DiscussionRepository as DiscussionRepositoryInterface;
use Ignite\Interfaces\Discussions\DiscussionResponse as DiscussionResponseInterface;
use Ignite\Interfaces\Discussions\DiscussionService as DiscussionServiceInterface;
use Ignite\Interfaces\Discussions\RepliesCollectionResponse as RepliesCollectionResponseInterface;
use Ignite\Interfaces\Discussions\Reply as ReplyInterface;
use Ignite\Interfaces\Discussions\ReplyFilter as ReplyFilterInterface;
use Ignite\Interfaces\Discussions\ReplyRepository as ReplyRepositoryInterface;
use Ignite\Interfaces\Discussions\ReplyResponse as ReplyResponseInterface;
use Ignite\Interfaces\Discussions\ReplyService as ReplyServiceInterface;
use Ignite\Interfaces\Discussions\UpdatesReplies;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class DiscussionsServiceProvider extends ServiceProvider
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
        $this->app->singleton(DiscussionFilterInterface::class, DiscussionFilter::class);
        $this->app->singleton(ReplyFilterInterface::class, ReplyFilter::class);
    }

    /**
     * Register all repository classes.
     *
     * @return void
     */
    public function registerRepositories(): void
    {
        $this->app->singleton(
            DiscussionRepositoryInterface::class,
            DiscussionRepository::class
        );

        $this->app->singleton(
            ReplyRepositoryInterface::class,
            ReplyRepository::class
        );
    }

    /**
     * Register all service classes.
     *
     * @return void
     */
    public function registerServices(): void
    {
        $this->app->singleton(DiscussionServiceInterface::class, function ($app) {
            return new DiscussionService($app->make(DiscussionRepositoryInterface::class));
        });

        $this->app->singleton(ReplyServiceInterface::class, function ($app) {
            return new ReplyService($app->make(ReplyRepositoryInterface::class));
        });
    }

    /**
     * Register all response classes.
     *
     * @return void
     */
    public function registerResponses(): void
    {
        $this->app->bind(DiscussionResponseInterface::class, DiscussionResponse::class);

        $this->app->bind(ReplyResponseInterface::class, ReplyResponse::class);
        $this->app->bind(
            RepliesCollectionResponseInterface::class,
            RepliesCollectionResponse::class
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
            DiscussionInterface::class,
            fn ($app) => $app->make(Discussion::class)
        );

        $this->app->singleton(
            ReplyInterface::class,
            fn ($app) => $app->make(Reply::class)
        );
    }

    /**
     * Register all action classes.
     *
     * @return void
     */
    protected function registerActions(): void
    {
        $this->app->singleton(UpdatesReplies::class, UpdateReply::class);
        $this->app->singleton(DeletesReplies::class, DeleteReply::class);
    }

    /**
     * Register resource resolution logic.
     *
     * @return void
     */
    protected function registerResolutionLogic(): void
    {
        Route::bind('discussion', function (string $value) {
            return $this->app->make(
                DiscussionServiceInterface::class
            )->find($value);
        });

        Route::bind('reply', function (string $value) {
            return $this->app->make(
                ReplyServiceInterface::class
            )->find($value);
        });
    }
}
