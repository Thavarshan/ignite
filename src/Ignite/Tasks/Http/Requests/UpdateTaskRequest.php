<?php

namespace Ignite\Tasks\Http\Requests;

use App\Http\Requests\Request;
use Ignite\Shared\Enums\Action;

class UpdateTaskRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->isAllowed(
            Action::UPDATE->value,
            $this->route('tasks')
        );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return $this->getRules($this->route('tasks'));
    }
}
