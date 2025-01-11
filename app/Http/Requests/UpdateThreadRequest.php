<?php

namespace App\Http\Requests;

use App\Http\Requests\Traits\AuthorizesRequests;
use Illuminate\Foundation\Http\FormRequest;

class UpdateThreadRequest extends FormRequest
{
    use AuthorizesRequests;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->isAllowedTo('update', $this->route('thread'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'channel_id' => ['sometimes', 'exists:channels,id'],
            'is_ticket' => ['nullable', 'boolean'],
            'assigned_to' => ['sometimes', 'exists:users,id'],
            'flagged' => ['nullable', 'boolean'],
            'locked' => ['nullable', 'boolean'],
            'pinned' => ['nullable', 'boolean'],
        ];
    }
}
