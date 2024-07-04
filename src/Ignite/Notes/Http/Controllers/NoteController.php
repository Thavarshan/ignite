<?php

namespace Ignite\Notes\Http\Controllers;

use App\Http\Controllers\Controller;
use Ignite\Interfaces\Notes\Note as NoteInterface;
use Ignite\Notes\Http\Requests\UpdateNoteRequest;
use Ignite\Notes\Http\Responses\NoteResponse;
use Ignite\Shared\Enums\Action;
use Illuminate\Contracts\Support\Responsable;
use Symfony\Component\HttpFoundation\Response;

class NoteController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param \Ignite\Interfaces\Notes\Note $note
     *
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Support\Responsable
     */
    public function show(NoteInterface $note): Response|Responsable
    {
        $this->authorize(Action::VIEW->value, $note);

        return NoteResponse::dispatch(compact('note'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Ignite\Notes\Http\Requests\UpdateNoteRequest $request
     * @param \Ignite\Interfaces\Notes\Note                 $note
     *
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Support\Responsable
     */
    public function update(
        UpdateNoteRequest $request,
        NoteInterface $note
    ): Response|Responsable {
        $note->update($request->validated());

        return NoteResponse::dispatch(compact('note'), Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Ignite\Interfaces\Notes\Note $note
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function destroy(NoteInterface $note): Response
    {
        $this->authorize(Action::DELETE, $note);

        $note->delete();

        return NoteResponse::dispatch(null, Response::HTTP_NO_CONTENT);
    }
}
