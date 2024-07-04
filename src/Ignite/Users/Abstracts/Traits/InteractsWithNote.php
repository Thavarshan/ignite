<?php

namespace Ignite\Users\Abstracts\Traits;

use Ignite\Interfaces\Notes\notable;
use Ignite\Interfaces\Notes\Note;

trait InteractsWithNote
{
    /**
     * The note instance.
     *
     * @var \Ignite\Interfaces\Notes\Note
     */
    protected Note $note;

    /**
     * Set the note instance.
     *
     * @param \Ignite\Interfaces\Notes\Note $note
     *
     * @return $this
     */
    public function setNote(Note $note): self
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get the note instance.
     *
     * @return \Ignite\Interfaces\Notes\Note
     */
    public function getNote(): Note
    {
        return $this->note;
    }

    /**
     * Get the Notable instance.
     *
     * @return \Ignite\Interfaces\Notes\Notable
     */
    public function getNotable(): notable
    {
        return $this->note->notable;
    }
}
