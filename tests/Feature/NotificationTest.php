<?php

use App\Models\Channel;
use App\Models\Reply;
use App\Models\Thread;
use App\Models\User;
use App\Notifications\BestAnswerMarked;
use App\Notifications\ReplyAdded;
use App\Notifications\ThreadReplied;
use App\Notifications\UserMentioned;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Notification;

test('replying to a thread notifies subscribers', function () {
    Notification::fake();

    $subscriber = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $thread->subscribe($subscriber);

    $replier = User::factory()->create();

    $this->actingAs($replier)
        ->postJson(route('api.threads.replies.store', $thread), ['body' => 'A reply.'])
        ->assertCreated();

    Notification::assertSentTo($subscriber, ThreadReplied::class);
});

test('replier does not get self-notified', function () {
    Notification::fake();

    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $thread->subscribe($user);

    $this->actingAs($user)
        ->postJson(route('api.threads.replies.store', $thread), ['body' => 'My own reply.'])
        ->assertCreated();

    Notification::assertNotSentTo($user, ThreadReplied::class);
});

test('nested reply notifies parent reply author', function () {
    Notification::fake();

    $parentAuthor = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $parent = Reply::factory()->for($thread)->for($parentAuthor)->create();

    $replier = User::factory()->create();

    $this->actingAs($replier)
        ->postJson(route('api.threads.replies.store', $thread), [
            'body' => 'Nested reply.',
            'parent_id' => $parent->id,
        ])
        ->assertCreated();

    Notification::assertSentTo($parentAuthor, ReplyAdded::class);
});

test('nested reply does not notify parent author if same user', function () {
    Notification::fake();

    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $parent = Reply::factory()->for($thread)->for($user)->create();

    $this->actingAs($user)
        ->postJson(route('api.threads.replies.store', $thread), [
            'body' => 'Replying to myself.',
            'parent_id' => $parent->id,
        ])
        ->assertCreated();

    Notification::assertNotSentTo($user, ReplyAdded::class);
});

test('mention in reply notifies mentioned user', function () {
    Notification::fake();

    $mentioned = User::factory()->create(['name' => 'taylor']);
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $replier = User::factory()->create();

    $this->actingAs($replier)
        ->postJson(route('api.threads.replies.store', $thread), ['body' => 'Hey @taylor check this!'])
        ->assertCreated();

    Notification::assertSentTo($mentioned, UserMentioned::class);
});

test('mentioning yourself does not send notification', function () {
    Notification::fake();

    $user = User::factory()->create(['name' => 'taylor']);
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();

    $this->actingAs($user)
        ->postJson(route('api.threads.replies.store', $thread), ['body' => 'I am @taylor'])
        ->assertCreated();

    Notification::assertNotSentTo($user, UserMentioned::class);
});

test('marking best answer notifies reply author', function () {
    Notification::fake();

    $owner = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for($owner)->create();
    $replyAuthor = User::factory()->create();
    $reply = Reply::factory()->for($thread)->for($replyAuthor)->create();

    $this->actingAs($owner)
        ->postJson(route('api.threads.best-reply.store', [$thread, $reply]))
        ->assertSuccessful();

    Notification::assertSentTo($replyAuthor, BestAnswerMarked::class);
});

test('marking own reply as best answer does not self-notify', function () {
    Notification::fake();

    $owner = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for($owner)->create();
    $reply = Reply::factory()->for($thread)->for($owner)->create();

    $this->actingAs($owner)
        ->postJson(route('api.threads.best-reply.store', [$thread, $reply]))
        ->assertSuccessful();

    Notification::assertNotSentTo($owner, BestAnswerMarked::class);
});

test('user can list their notifications', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $reply = Reply::factory()->for($thread)->for(User::factory())->create();
    $user->notify(new ThreadReplied($thread, $reply));

    $this->actingAs($user)
        ->getJson(route('api.notifications.index'))
        ->assertSuccessful()
        ->assertJsonCount(1, 'data');
});

test('user can mark a notification as read', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $reply = Reply::factory()->for($thread)->for(User::factory())->create();
    $user->notify(new ThreadReplied($thread, $reply));
    $notification = $user->notifications()->first();

    $this->actingAs($user)
        ->postJson(route('api.notifications.read', $notification->id))
        ->assertSuccessful();

    expect($notification->fresh()->read_at)->not->toBeNull();
});

test('user can mark all notifications as read', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $reply = Reply::factory()->for($thread)->for(User::factory())->create();
    $user->notify(new ThreadReplied($thread, $reply));
    $user->notify(new ThreadReplied($thread, $reply));

    $this->actingAs($user)
        ->postJson(route('api.notifications.read-all'))
        ->assertSuccessful();

    expect($user->unreadNotifications()->count())->toBe(0);
});

test('user can delete a notification', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $reply = Reply::factory()->for($thread)->for(User::factory())->create();
    $user->notify(new ThreadReplied($thread, $reply));
    $notification = $user->notifications()->first();

    $this->actingAs($user)
        ->deleteJson(route('api.notifications.destroy', $notification->id))
        ->assertNoContent();

    expect($user->notifications()->count())->toBe(0);
});

test('email is sent by default for thread replied notification', function () {
    $subscriber = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $reply = Reply::factory()->for($thread)->for(User::factory())->create();

    $notification = new ThreadReplied($thread, $reply);

    expect($notification->via($subscriber))->toContain('mail')
        ->and($notification->toMail($subscriber))->toBeInstanceOf(MailMessage::class);
});

test('email is skipped when user disables thread_replied preference', function () {
    $subscriber = User::factory()->create([
        'notification_preferences' => ['thread_replied' => false],
    ]);
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $reply = Reply::factory()->for($thread)->for(User::factory())->create();

    $notification = new ThreadReplied($thread, $reply);

    expect($notification->via($subscriber))->not->toContain('mail')
        ->and($notification->via($subscriber))->toContain('database');
});

test('email is sent by default for reply added notification', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $parent = Reply::factory()->for($thread)->for($user)->create();
    $child = Reply::factory()->for($thread)->for(User::factory())->create();

    $notification = new ReplyAdded($parent, $child);

    expect($notification->via($user))->toContain('mail')
        ->and($notification->toMail($user))->toBeInstanceOf(MailMessage::class);
});

test('email is skipped when user disables reply_added preference', function () {
    $user = User::factory()->create([
        'notification_preferences' => ['reply_added' => false],
    ]);
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $parent = Reply::factory()->for($thread)->for($user)->create();
    $child = Reply::factory()->for($thread)->for(User::factory())->create();

    $notification = new ReplyAdded($parent, $child);

    expect($notification->via($user))->not->toContain('mail');
});

test('email is sent by default for best answer marked notification', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $reply = Reply::factory()->for($thread)->for($user)->create();

    $notification = new BestAnswerMarked($thread, $reply);

    expect($notification->via($user))->toContain('mail')
        ->and($notification->toMail($user))->toBeInstanceOf(MailMessage::class);
});

test('email is skipped when user disables best_answer_marked preference', function () {
    $user = User::factory()->create([
        'notification_preferences' => ['best_answer_marked' => false],
    ]);
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $reply = Reply::factory()->for($thread)->for($user)->create();

    $notification = new BestAnswerMarked($thread, $reply);

    expect($notification->via($user))->not->toContain('mail');
});

test('email is sent by default for user mentioned notification', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();

    $notification = new UserMentioned($thread, 'JohnDoe');

    expect($notification->via($user))->toContain('mail')
        ->and($notification->toMail($user))->toBeInstanceOf(MailMessage::class);
});

test('email is skipped when user disables user_mentioned preference', function () {
    $user = User::factory()->create([
        'notification_preferences' => ['user_mentioned' => false],
    ]);
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();

    $notification = new UserMentioned($thread, 'JohnDoe');

    expect($notification->via($user))->not->toContain('mail');
});

test('wantsEmailFor defaults to true for unset preferences', function () {
    $user = User::factory()->create(['notification_preferences' => null]);

    expect($user->wantsEmailFor('thread_replied'))->toBeTrue()
        ->and($user->wantsEmailFor('reply_added'))->toBeTrue()
        ->and($user->wantsEmailFor('best_answer_marked'))->toBeTrue()
        ->and($user->wantsEmailFor('user_mentioned'))->toBeTrue();
});

test('wantsEmailFor respects individual preferences', function () {
    $user = User::factory()->create([
        'notification_preferences' => [
            'thread_replied' => false,
            'reply_added' => true,
        ],
    ]);

    expect($user->wantsEmailFor('thread_replied'))->toBeFalse()
        ->and($user->wantsEmailFor('reply_added'))->toBeTrue()
        ->and($user->wantsEmailFor('best_answer_marked'))->toBeTrue();
});
