<?php

namespace Ignite\Auth\Http\Requests;

use App\Http\Requests\Request;
use Ignite\Auth\Models\Permission;
use Ignite\Shared\Enums\Action;
use Ignite\Shared\Enums\Resource;

class StorePermissionRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->isAllowed(Action::CREATE->value, Permission::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return $this->getRules(Resource::PERMISSIONS->value);
    }
}
