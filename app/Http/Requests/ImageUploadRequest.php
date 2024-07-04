<?php

namespace App\Http\Requests;

use Ignite\Shared\Enums\Action;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ImageUploadRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->isAllowed(Action::CREATE->value, Media::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'image' => ['required', 'image', 'max:2048', 'mimes:jpeg,jpg,png'],
        ];
    }
}
