<?php

namespace App\Http\Requests;

use App\Models\Thread;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreThreadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('create', Thread::class) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, string|Rule>>
     */
    public function rules(): array
    {
        $rules = [
            'channel_id' => ['required', 'integer', 'exists:channels,id'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'alpha_dash:ascii', Rule::unique(Thread::class, 'slug')],
            'body' => ['required', 'string'],
        ];

        if ($this->user()?->isAdmin()) {
            $rules['locked'] = ['sometimes', 'boolean'];
            $rules['pinned'] = ['sometimes', 'boolean'];
        }

        return $rules;
    }
}
