<?php

namespace App\Http\Requests;

use App\Http\Requests\Traits\AuthorizesRequests;
use App\Models\Channel;
use Illuminate\Foundation\Http\FormRequest;

class StoreChannelRequest extends FormRequest
{
    use AuthorizesRequests;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->isAllowedTo('create', Channel::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ];
    }
}
