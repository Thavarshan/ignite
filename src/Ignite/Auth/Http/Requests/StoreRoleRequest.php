<?php

namespace Ignite\Auth\Http\Requests;

use App\Http\Requests\Request;
use Ignite\Auth\Models\Role;
use Ignite\Shared\Enums\Action;

class StoreRoleRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->isAllowed(Action::CREATE->value, Role::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return $this->getRules('roles');
    }
}
