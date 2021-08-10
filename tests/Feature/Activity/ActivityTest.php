<?php

namespace Tests\Feature\Activity;

use App\Models\Activity;
use App\Models\Reply;
use App\Models\Thread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ActivityTest extends TestCase
{
    use RefreshDatabase;

    public function testRecordActivityOnThreadCreate()
    {
        $this->signIn();

        $thread = create(Thread::class);

        $this->assertDatabaseHas('activities',[
            'type' => 'created_thread',
            'user_id' => Auth::user()->id,
            'subject_id' => $thread->id,
            'subject_type' => get_class($thread),
        ]);

        $activity = Activity::first();
        $this->assertEquals($activity->subject->id, $thread->id);
    }

    public function testRecordActivityOnReplyCreate()
    {
        $this->signIn();

        create(Reply::class);

        $this->assertEquals(2,Activity::count());
    }
}
