<?php

namespace Ignite\Activities\Http\Requests;

use App\Http\Requests\Request;
use App\Support\InteractsWithContainer;
use Ignite\Interfaces\Users\User;
use Ignite\Interfaces\Users\UserService;

class FecthActivityRequest extends Request
{
    use InteractsWithContainer;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [];
    }

    /**
     * Get the specific user.
     *
     * @return \Ignite\Interfaces\Users\User
     */
    public function forUser(): User
    {
        return $this->resolve(UserService::class)
            ->find($this->query('user'));
    }

    /**
     * Determine if the request is paginated.
     *
     * @return bool
     */
    public function paginated(): bool
    {
        return $this->boolean('paginated', false);
    }
}
