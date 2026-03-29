<?php

namespace App\Notifications;

use App\Models\Reply;
use App\Models\Thread;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserMentioned extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Thread|Reply $mentionable,
        public string $mentionerName,
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        $channels = ['database'];

        if ($notifiable->wantsEmailFor('user_mentioned')) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $type = $this->mentionable instanceof Thread ? 'thread' : 'reply';
        $thread = $this->mentionable instanceof Thread
            ? $this->mentionable
            : $this->mentionable->thread;

        return (new MailMessage)
            ->subject("{$this->mentionerName} mentioned you")
            ->line("You were mentioned by {$this->mentionerName} in a {$type}.")
            ->action('View', route('threads.show', $thread));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $thread = $this->mentionable instanceof Thread
            ? $this->mentionable
            : $this->mentionable->thread;

        return [
            'mentionable_type' => $this->mentionable->getMorphClass(),
            'mentionable_id' => $this->mentionable->id,
            'mentioner_name' => $this->mentionerName,
            'thread_slug' => $thread->slug,
            'thread_title' => $thread->title,
        ];
    }
}
