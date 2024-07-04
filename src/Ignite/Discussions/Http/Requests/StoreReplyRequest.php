<?php

namespace Ignite\Discussions\Http\Requests;

use App\Http\Requests\Request;
use Ignite\Discussions\Models\Reply;
use Ignite\Shared\Enums\Action;
use Ignite\Shared\Enums\Resource;

class StoreReplyRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->isAllowed(Action::VIEW->value, $this->route('discussion'))
            && $this->isAllowed(Action::CREATE->value, Reply::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return $this->getRules(Resource::REPLIES->value);
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->setErrorBag('storeReply');
    }
}
