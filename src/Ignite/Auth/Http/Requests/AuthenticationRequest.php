<?php

namespace Ignite\Auth\Http\Requests;

use App\Http\Requests\Request;
use Ignite\Auth\DTO\Credentials;
use Ignite\Auth\Facades\Auth;
use Ignite\Interfaces\Auth\AuthenticationRequest as AuthenticationRequestInterface;
use Ignite\Interfaces\Auth\Credentials as CredentialsInterface;
use Ignite\Interfaces\Users\User;
use Illuminate\Support\Str;

class AuthenticationRequest extends Request implements AuthenticationRequestInterface
{
    /**
     * The authenticated user.
     *
     * @var \Ignite\Interfaces\Users\User|null
     */
    protected ?User $authUser = null;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->isGuest();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            Auth::username('email') => ['required', 'string'],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Get the form input data used for authentication.
     *
     * @return \Ignite\Interfaces\Auth\Credentials
     */
    public function getCredentials(): CredentialsInterface
    {
        return new Credentials(
            ...array_values($this->only(['email', 'password']))
        );
    }

    /**
     * Get the username input value.
     *
     * @return string
     */
    public function username(): string
    {
        return $this->get(Auth::username('email'), '');
    }

    /**
     * Get the password input value.
     *
     * @return string
     */
    public function password(): string
    {
        return $this->get(Auth::password('password'), '');
    }

    /**
     * Determine if the user should be "remembered".
     *
     * @return bool
     */
    public function remember(): bool
    {
        return $this->boolean('remember');
    }

    /**
     * Get the authenticated user.
     *
     * @return \Ignite\Interfaces\Users\User|null
     */
    public function getAuthUser(): ?User
    {
        return $this->authUser;
    }

    /**
     * Set the authenticated user.
     *
     * @param \Ignite\Interfaces\Users\User $user
     *
     * @return void
     */
    public function setAuthUser(User $user): void
    {
        $this->authUser = $user;
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge(['email' => Str::lower($this->email)]);

        $this->setErrorBag('authenticateUser');
    }
}
