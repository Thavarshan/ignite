<?php

namespace Ignite\Auth\Http\Requests;

use App\Http\Requests\Request;

class NewPasswordRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return $this->getRulesFor('new_password');
    }
}
