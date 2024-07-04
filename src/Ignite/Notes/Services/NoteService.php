<?php

namespace Ignite\Notes\Services;

use Ignite\Interfaces\Notes\Notable;
use Ignite\Interfaces\Notes\NoteRepository;
use Ignite\Interfaces\Notes\NoteService as NoteServiceInterface;
use Ignite\Shared\Data\Services\Service;
use Illuminate\Database\Eloquent\Collection;

class NoteService extends Service implements NoteServiceInterface
{
    /**
     * Create a new service instance.
     *
     * @param \Ignite\Interfaces\Notes\NoteRepository $repository
     *
     * @return void
     */
    public function __construct(NoteRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * List all data entries of the notable model.
     *
     * @param \Ignite\Interfaces\Notes\Notable $notable
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function list(Notable $notable): Collection
    {
        $notable->load('notes');

        return $notable->notes;
    }
}
