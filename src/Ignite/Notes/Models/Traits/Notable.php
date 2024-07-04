<?php

namespace Ignite\Notes\Models\Traits;

use Ignite\Interfaces\Notes\Note as NoteInterface;
use Ignite\Notes\Models\Note;
use Ignite\Shared\Data\Traits\Fillable;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Notable
{
    use Fillable;

    /**
     * Get all of the model's notes.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function notes(): MorphMany
    {
        return $this->morphMany(Note::class, 'notable');
    }

    /**
     * Add a note to the model.
     *
     * @param string      $message
     * @param string|null $type
     *
     * @return \Ignite\Interfaces\Notes\Note
     */
    public function addNote(array $data): NoteInterface
    {
        $data['user_id'] = $data['user_id'] ?? auth()->id();

        return $this->notes()->create(
            $this->filterFillable($data, Note::class)
        );
    }

    /**
     * Update a note on the model.
     *
     * @param \Ignite\Interfaces\Notes\Note $note
     * @param array                         $data
     *
     * @return \Ignite\Interfaces\Notes\Note
     */
    public function updateNote(
        NoteInterface $note,
        array $data
    ): NoteInterface {
        $note->update(
            $this->filterFillable($data, Note::class)
        );

        return $note;
    }
}
