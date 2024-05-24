<?php

namespace Ignite\Users\Http\Requests;

use App\Http\Requests\Request;
use Ignite\Organisations\Rules\InvitationEmailRule;
use Ignite\Shared\Enums\Action;
use Ignite\Shared\Enums\Resource;
use Ignite\Users\Models\User;

class StoreUserRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->isAllowed(Action::CREATE->value, User::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return $this->getRulesFor(
            Resource::USERS->value,
            $this->has('organisation_id') ? [] : [
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                    'unique:' . Resource::USERS->value,
                    $this->make(InvitationEmailRule::class),
                ],
            ]
        );
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->setErrorBag('storeUser');
    }
}
