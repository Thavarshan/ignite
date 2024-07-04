<?php

namespace Ignite\Auth\Http\Requests;

use App\Http\Requests\Request;
use Ignite\Shared\Enums\Action;
use Ignite\Shared\Enums\Resource;

class UpdateRoleRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->isAllowed(Action::UPDATE->value, $this->route('role'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return $this->getRules(Resource::ROLES->value);
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->setErrorBag('updateRole');
    }
}
