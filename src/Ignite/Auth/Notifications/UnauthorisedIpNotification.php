<?php

namespace Ignite\Auth\Notifications;

use Ignite\Auth\Mail\LogginAttemptedWithUnauthorisedIpMail;
use Ignite\Interfaces\Auth\Ip;
use Ignite\Interfaces\Users\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Notifications\Notification;

class UnauthorisedIpNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @param \Ignite\Interfaces\Users\User $user
     *
     * @return void
     */
    public function __construct(protected readonly User $user)
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param \Ignite\Interfaces\Users\User $notifiable
     *
     * @return \Illuminate\Mail\Mailable
     */
    public function toMail(object $notifiable): Mailable
    {
        return (new LogginAttemptedWithUnauthorisedIpMail(
            user: $this->getUser(),
            ip: $this->getIp()
        ))->to($notifiable);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'user' => $this->getUser(),
            'ip' => $this->getIp(),
        ];
    }

    /**
     * Get the user instance.
     *
     * @return \Ignite\Interfaces\Users\User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * Get the ip instance.
     *
     * @return \Ignite\Interfaces\Auth\Ip
     */
    public function getIp(): Ip
    {
        return $this->user->getIp();
    }
}
