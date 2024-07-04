<?php

namespace Ignite\Users\Http\Requests;

use App\Http\Requests\Request;
use Ignite\Shared\Enums\Action;
use Ignite\Shared\Enums\Resource;
use Ignite\Shared\Rules\PasswordValidationRules;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends Request
{
    use PasswordValidationRules;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->isAllowed(Action::UPDATE->value, $this->route('user'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return $this->getRulesFor(Resource::USERS->value, [
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore(
                    $this->route('user') ?? $this->user()
                ),
            ],
            'password' => $this->passwordRules(['nullable']),
            'organisation_id' => ['nullable', 'integer', 'exists:' . Resource::ORGANISATIONS->value . ',id'],
            'current_team_id' => ['nullable', 'integer', 'exists:' . Resource::TEAMS->value . ',id'],
        ]);
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->setErrorBag('updateUser');
    }
}
