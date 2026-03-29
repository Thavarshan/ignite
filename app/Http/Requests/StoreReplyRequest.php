<?php

namespace App\Http\Requests;

use App\Models\Reply;
use App\Models\Thread;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreReplyRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var Thread $thread */
        $thread = $this->route('thread');

        return $this->user()?->can('create', [Reply::class, $thread]) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        /** @var Thread $thread */
        $thread = $this->route('thread');

        return [
            'body' => ['required', 'string'],
            'parent_id' => [
                'nullable',
                'integer',
                Rule::exists('replies', 'id')->where('thread_id', $thread->id)->whereNull('parent_id'),
            ],
        ];
    }
}
