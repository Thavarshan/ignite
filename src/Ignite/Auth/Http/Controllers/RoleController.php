<?php

namespace Ignite\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use Ignite\Auth\Enums\Role as Roles;
use Ignite\Auth\Http\Requests\StoreRoleRequest;
use Ignite\Auth\Http\Requests\UpdateRoleRequest;
use Ignite\Auth\Http\Responses\RoleResponse;
use Ignite\Auth\Http\Responses\RolesCollectionResponse;
use Ignite\Auth\Models\Role;
use Ignite\Interfaces\Auth\Role as RoleInterface;
use Ignite\Interfaces\Auth\RoleFilter;
use Ignite\Interfaces\Auth\RoleService;
use Ignite\Shared\Enums\Action;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Facades\Response as ResponseFacade;
use Symfony\Component\HttpFoundation\Response;

class RoleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param \Ignite\Interfaces\Auth\RoleService $service
     *
     * @return void
     */
    public function __construct(protected readonly RoleService $service)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Ignite\Interfaces\Auth\RoleFilter $filter
     *
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Support\Responsable
     */
    public function index(RoleFilter $filter): Response|Responsable
    {
        $this->authorize(Action::VIEW_ANY->value, Role::class);

        /** @var \Illuminate\Eloquent\Database\Collection<\Ignite\Interfaces\Auth\Role> $roles */
        $roles = $this->service->list($filter)->get();

        $roles->map(function (RoleInterface $role) {
            $role->is_default = $role->getName() === Roles::default();

            return $role;
        });

        return RolesCollectionResponse::dispatch(
            content: $roles,
            status: Response::HTTP_OK,
            viewName: 'Roles/Index',
            resourceKey: 'roles'
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Ignite\Auth\Http\Requests\StoreRoleRequest $request
     *
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Support\Responsable
     */
    public function store(StoreRoleRequest $request): Response|Responsable
    {
        $role = $this->service->create($request->validated());

        return RoleResponse::dispatch($role, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param \Ignite\Interfaces\Auth\Role $role
     *
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Support\Responsable
     */
    public function show(RoleInterface $role): Response|Responsable
    {
        $this->authorize(Action::VIEW->value, $role);

        return RoleResponse::dispatch($role);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Ignite\Auth\Http\Requests\UpdateRoleRequest $request
     * @param \Ignite\Interfaces\Auth\Role                 $role
     *
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Support\Responsable
     */
    public function update(
        UpdateRoleRequest $request,
        RoleInterface $role
    ): Response|Responsable {
        $role = $this->service->update($role, $request->validated());

        return RoleResponse::dispatch($role);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Ignite\Interfaces\Auth\Role $role
     *
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Support\Responsable
     */
    public function destroy(RoleInterface $role): Response|Responsable
    {
        $this->authorize(Action::DELETE->value, $role);

        $this->service->delete($role);

        return ResponseFacade::noContent();
    }
}
