<?php

namespace Ignite\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use Ignite\Auth\Http\Requests\StorePermissionRequest;
use Ignite\Auth\Http\Requests\UpdatePermissionRequest;
use Ignite\Auth\Http\Responses\PermissionResponse;
use Ignite\Auth\Http\Responses\PermissionsCollectionResponse;
use Ignite\Auth\Models\Permission;
use Ignite\Interfaces\Auth\Permission as PermissionInterface;
use Ignite\Interfaces\Auth\PermissionFilter;
use Ignite\Interfaces\Auth\PermissionService;
use Ignite\Shared\Enums\Action;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Facades\Response as ResponseFacade;
use Symfony\Component\HttpFoundation\Response;

class PermissionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param \Ignite\Interfaces\Auth\PermissionService $service
     *
     * @return void
     */
    public function __construct(protected readonly PermissionService $service)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Ignite\Interfaces\Auth\PermissionFilter $filter
     *
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Support\Responsable
     */
    public function index(PermissionFilter $filter): mixed
    {
        $this->authorize(Action::VIEW_ANY->value, Permission::class);

        return PermissionsCollectionResponse::dispatch(
            $this->service->list($filter)->get()
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Ignite\Auth\Http\Requests\StorePermissionRequest $request
     *
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Support\Responsable
     */
    public function store(StorePermissionRequest $request): Response|Responsable
    {
        $permission = $this->service->create($request->validated());

        return PermissionResponse::dispatch($permission, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param \Ignite\Interfaces\Auth\Permission $permission
     *
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Support\Responsable
     */
    public function show(PermissionInterface $permission): Response|Responsable
    {
        $this->authorize(Action::VIEW->value, $permission);

        return PermissionResponse::dispatch($permission);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Ignite\Auth\Http\Requests\UpdatePermissionRequest $request
     * @param \Ignite\Interfaces\Auth\Permission                 $permission
     *
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Support\Responsable
     */
    public function update(
        UpdatePermissionRequest $request,
        PermissionInterface $permission
    ): Response|Responsable {
        $permission = $this->service->update($permission, $request->validated());

        return PermissionResponse::dispatch($permission);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Ignite\Interfaces\Auth\Permission $permission
     *
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Support\Responsable
     */
    public function destroy(PermissionInterface $permission): Response|Responsable
    {
        $this->authorize(Action::DELETE->value, $permission);

        $this->service->delete($permission);

        return ResponseFacade::noContent();
    }
}
