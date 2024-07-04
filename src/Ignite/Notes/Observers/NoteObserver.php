<?php

namespace Ignite\Notes\Observers;

use App\Support\InteractsWithContainer;
use Ignite\Interfaces\Notes\NotifiesNoteRelatedParties;
use Ignite\Notes\Models\Note;
use Ignite\Notes\Notifications\NewNote;

class NoteObserver
{
    use InteractsWithContainer;

    /**
     * Handle the Note "created" event.
     *
     * @param \Ignite\Notes\Models\Note $note
     *
     * @return void
     */
    public function created(Note $note): void
    {
        $this->make(NotifiesNoteRelatedParties::class)
            ->notify($note, new NewNote($note));
    }

    /**
     * Handle the Note "updated" event.
     *
     * @param \Ignite\Notes\Models\Note $note
     *
     * @return void
     */
    public function updated(Note $note): void
    {
    }

    /**
     * Handle the Note "deleted" event.
     *
     * @param \Ignite\Notes\Models\Note $note
     *
     * @return void
     */
    public function deleted(Note $note): void
    {
    }
}
