<?php

namespace Ignite\Tasks\Http\Controllers;

use App\Http\Controllers\Controller;
use Ignite\Interfaces\Tasks\CreatesNewTasks;
use Ignite\Interfaces\Tasks\DeletesTasks;
use Ignite\Interfaces\Tasks\Task as TaskInterface;
use Ignite\Interfaces\Tasks\TaskFilter;
use Ignite\Interfaces\Tasks\TaskService;
use Ignite\Interfaces\Tasks\UpdatesTaskInformation;
use Ignite\Shared\Enums\Action;
use Ignite\Tasks\Http\Requests\StoreTaskRequest;
use Ignite\Tasks\Http\Requests\UpdateTaskRequest;
use Ignite\Tasks\Http\Responses\TaskResponse;
use Ignite\Tasks\Http\Responses\TasksCollectionResponse;
use Ignite\Tasks\Models\Task;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param \Illuminate\Http\Request             $request
     * @param \Ignite\Interfaces\Tasks\TaskFilter  $filter
     * @param \Ignite\Interfaces\Tasks\TaskService $service
     *
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Support\Responsable
     */
    public function index(
        Request $request,
        TaskFilter $filter,
        TaskService $service
    ): Response|Responsable {
        $this->authorize(Action::VIEW_ANY->value, Task::class);

        $tasks = $service->list( // @phpstan-ignore-line
            $filter,
            paginated: true,
            user: $request->user(),
        )->withQueryString();

        return TasksCollectionResponse::dispatch(compact('tasks'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Ignite\Tasks\Http\Requests\StoreTaskRequest $request
     * @param \Ignite\Interfaces\Tasks\CreatesNewTasks     $creator
     *
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Support\Responsable
     */
    public function store(
        StoreTaskRequest $request,
        CreatesNewTasks $creator
    ): Response|Responsable {
        $task = $creator->create($request->validated());

        return TaskResponse::dispatch($task, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param \Ignite\Interfaces\Tasks\Task $task
     *
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Support\Responsable
     */
    public function show(TaskInterface $task): Response|Responsable
    {
        $this->authorize(Action::VIEW->value, $task);

        return TaskResponse::dispatch($task);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Ignite\Tasks\Http\Requests\UpdateTaskRequest   $request
     * @param \Ignite\Interfaces\Tasks\UpdatesTaskInformation $updater
     * @param \Ignite\Interfaces\Tasks\Task                   $task
     *
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Support\Responsable
     */
    public function update(
        UpdateTaskRequest $request,
        UpdatesTaskInformation $updater,
        TaskInterface $task
    ): Response|Responsable {
        $task = $updater->update($task, $request->validated());

        return TaskResponse::dispatch($task);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Ignite\Interfaces\Tasks\Task $task
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function destroy(TaskInterface $task, DeletesTasks $deleter): Response
    {
        $this->authorize(Action::DELETE, $task);

        $deleter->delete($task);

        return TaskResponse::dispatch(null, Response::HTTP_NO_CONTENT);
    }
}
