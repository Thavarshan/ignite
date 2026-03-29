<?php

namespace App\Notifications;

use App\Models\Reply;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReplyAdded extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Reply $parentReply,
        public Reply $childReply,
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        $channels = ['database'];

        if ($notifiable->wantsEmailFor('reply_added')) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New reply to your comment')
            ->line("{$this->childReply->user->name} replied to your comment.")
            ->line(str($this->childReply->body)->limit(200))
            ->action('View Thread', route('threads.show', $this->parentReply->thread));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'thread_id' => $this->parentReply->thread_id,
            'thread_slug' => $this->parentReply->thread->slug,
            'parent_reply_id' => $this->parentReply->id,
            'reply_id' => $this->childReply->id,
            'replier_id' => $this->childReply->user_id,
            'replier_name' => $this->childReply->user->name,
        ];
    }
}
