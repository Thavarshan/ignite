<?php

namespace Ignite\Users\Http\Requests;

use Ignite\Shared\Enums\Action;

class GetCurrentUserRequest extends CurrentUserRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->isAllowed(
            Action::VIEW->value,
            $this->user(),
            withoutAuthentication: false
        );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [];
    }
}
