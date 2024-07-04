<?php

namespace Ignite\Statuses\Http\Requests;

use App\Http\Requests\Request;
use Ignite\Statuses\Enums\Status;
use Illuminate\Validation\Rule;

class SetStatusRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->isAuthenticated();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'status' => ['required', 'string', Rule::enum(Status::class)],
        ];
    }

    /**
     * Get the status from the request.
     *
     * @return \Ignite\Statuses\Enums\Status
     */
    public function status(): Status
    {
        return Status::isValid($this->input('status'));
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->setErrorBag('updateStatus');
    }
}
