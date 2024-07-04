<?php

namespace Ignite\Auth\Listeners;

use Ignite\Auth\Events\IpNotAuthorised;
use Ignite\Auth\Notifications\UnauthorisedIpNotification;
use Ignite\Interfaces\Organisations\Organisation;

class NotifyOrganisationOfUnauthorisedIp
{
    /**
     * Handle the event.
     *
     * @param \Ignite\Auth\Events\IpNotAuthorised $event
     *
     * @return void
     */
    public function handle(IpNotAuthorised $event): void
    {
        dispatch(fn () => $event->relatedOrganisation(function (
            Organisation $organisation
        ) use ($event): void {
            $organisation->notifyManagers(
                new UnauthorisedIpNotification($event->getUser())
            );
        }));
    }
}
