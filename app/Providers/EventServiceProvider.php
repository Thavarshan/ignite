<?php

namespace App\Providers;

use Ignite\Auth\Events\IpNotAuthorised;
use Ignite\Auth\Listeners\NotifyOrganisationOfUnauthorisedIp;
use Ignite\Billing\Events\NewPaymentMade;
use Ignite\Billing\Listeners\GeneratePaymentDocuments;
use Ignite\Instructions\Events\BaseInstructionCreated;
use Ignite\Instructions\Events\InstructionProcessingCompleted;
use Ignite\Instructions\Events\InstructionReportUploaded;
use Ignite\Instructions\Listeners\InitiateInstructionProcessing;
use Ignite\Instructions\Listeners\NotifyRelatedParties;
use Ignite\Instructions\Listeners\NotifyStaffOfUpload;
use Ignite\Statuses\Events\StatusUpdated;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        IpNotAuthorised::class => [
            NotifyOrganisationOfUnauthorisedIp::class,
        ],

        BaseInstructionCreated::class => [
            InitiateInstructionProcessing::class,
        ],

        InstructionProcessingCompleted::class => [
            NotifyRelatedParties::class,
        ],

        InstructionReportUploaded::class => [
            NotifyStaffOfUpload::class,
        ],

        StatusUpdated::class => [],

        NewPaymentMade::class => [
            GeneratePaymentDocuments::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return true;
    }
}
