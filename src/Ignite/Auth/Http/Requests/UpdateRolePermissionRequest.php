<?php

namespace Ignite\Auth\Http\Requests;

use App\Http\Requests\Request;
use Ignite\Auth\Models\Role;
use Ignite\Shared\Enums\Action;

class UpdateRolePermissionRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->isAllowed(Action::UPDATE->value, Role::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'permissions' => ['required', 'array'],
            'permissions.*' => ['required', 'string', 'exists:permissions,name'],
        ];
    }
}
