<?php

namespace Ignite\Notes\Repositories;

use Ignite\Interfaces\Notes\Note;
use Ignite\Interfaces\Notes\NoteRepository as NoteRepositoryInterface;
use Ignite\Shared\Data\Repositories\Repository;

class NoteRepository extends Repository implements NoteRepositoryInterface
{
    /**
     * Create a new repository instance.
     *
     * @param \Ignite\Interfaces\Notes\Note $note
     *
     * @return void
     */
    public function __construct(Note $note)
    {
        $this->model = $note;
    }
}
