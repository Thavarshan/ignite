<?php

namespace Ignite\Notes\Abstracts;

use Ignite\Interfaces\Notes\Note;
use Ignite\Users\Abstracts\Traits\InteractsWithNote;

abstract class NoteAbstract
{
    use InteractsWithNote;

    /**
     * Create a new abstract note instance.
     *
     * @param \Ignite\Interfaces\Notes\Note $note
     *
     * @return void
     */
    public function __construct(Note $note)
    {
        $this->note = $note;
    }
}
