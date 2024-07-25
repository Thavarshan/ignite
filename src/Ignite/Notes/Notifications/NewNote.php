<?php

namespace Ignite\Notes\Notifications;

use Ignite\Interfaces\Notes\Note;
use Ignite\Users\Abstracts\Traits\InteractsWithNote;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackAttachment;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class NewNote extends Notification
{
    use Queueable;
    use InteractsWithNote;

    /**
     * Create a new notification instance.
     *
     * @param \Ignite\Interfaces\Notes\Note $note
     *
     * @return void
     */
    public function __construct(Note $note)
    {
        $this->setNote($note->load('user'));
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'slack'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail(): MailMessage
    {
        return (new MailMessage())
            ->subject(sprintf('New note about - %s', $this->note->getTitle()))
            ->greeting(sprintf('A new note has been added by %s', $this->note->getUserName()))
            ->line($this->note->getBody())
            ->action('View note', $this->note->getPath());
    }

    /**
     * Get the Slack representation of the notification.
     *
     * @return \Illuminate\Notifications\Messages\SlackMessage
     */
    public function toSlack(): SlackMessage
    {
        return (new SlackMessage())
            ->info()
            ->from('Ignite')
            ->attachment(function (SlackAttachment $attachment) {
                $title = sprintf(
                    'A new note has been added by %s',
                    $this->note->getUserName()
                );

                $attachment->title($title)
                    ->fields([
                        'body' => $this->note->getBody(),
                        'email' => $this->note->user->getEmail(),
                    ]);
            });
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [

        ];
    }
}
