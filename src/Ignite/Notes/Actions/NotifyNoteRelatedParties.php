<?php

namespace Ignite\Notes\Actions;

use App\Enums\Notification as Channel;
use App\Support\Arr;
use Ignite\Interfaces\Notes\Notable;
use Ignite\Interfaces\Notes\Note;
use Ignite\Interfaces\Notes\NotifiesNoteRelatedParties;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Notification as Facade;

class NotifyNoteRelatedParties implements NotifiesNoteRelatedParties
{
    /**
     * Notify the related parties of the note.
     *
     * @param \Ignite\Interfaces\Notes\Note          $note
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @return void
     */
    public function notify(Note $note, Notification $notification): void
    {
        // Client users used to be able to post notes but since notes have been
        // made private and should only be shared between NW staff and
        // admin members, we don't want to notify client users.
        // if (! $note->user->hasAnyRole([
        //     Role::STAFF->value,
        //     Role::ADMIN->value
        // ])) {
        //     Facade::route(
        //         Channel::SLACK->value,
        //         config('services.slack.notifications')
        //     )->notify($notification);
        // }

        tap($note->getNotable(), function (Notable $notable) use ($note, $notification) {
            $emails = $this->resolveEmails($notable);

            if (! blank($emails)) {
                collect($emails)
                    ->filter(fn ($email) => $email !== $note->user->getEmail())
                    ->each(function ($email) use ($notification) {
                        Facade::route(
                            Channel::MAIL->value,
                            $email
                        )->notify($notification);
                    });
            }
        });
    }

    /**
     * Resolve the email address from a Notable instance.
     *
     * @param \Ignite\Interfaces\Notes\Notable $notable
     *
     * @return array|null
     */
    protected function resolveEmails(Notable $notable): ?array
    {
        if (method_exists($notable, 'getNotifiableEmails')) {
            // "cc" becuase "to" would mean sending to a client_handler user
            // but since notes are private and should only be shared
            // between NW staff and admin members.
            return Arr::get($notable->getNotifiableEmails(), 'cc');
        }

        return null;
    }
}
