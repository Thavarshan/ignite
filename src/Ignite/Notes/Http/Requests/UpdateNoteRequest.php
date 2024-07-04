<?php

namespace Ignite\Notes\Http\Requests;

use App\Http\Requests\Request;
use Ignite\Shared\Enums\Action;
use Ignite\Shared\Enums\Resource;

class UpdateNoteRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->isAllowed(Action::UPDATE->value, Resource::NOTES->value);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return $this->getRules(Resource::NOTES->value);
    }
}
