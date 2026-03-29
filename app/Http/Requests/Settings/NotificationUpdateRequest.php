<?php

namespace App\Http\Requests\Settings;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class NotificationUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'thread_replied' => ['required', 'boolean'],
            'best_answer_marked' => ['required', 'boolean'],
            'user_mentioned' => ['required', 'boolean'],
            'reply_added' => ['required', 'boolean'],
        ];
    }
}
