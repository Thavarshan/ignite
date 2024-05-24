# Password Reset

## `GET` Route

**Displaying the Password Reset Form**

```
GET /reset-password/{token}
```

### Parameters

| Name  | Type   | In    | Description                                                       |
| ----- | ------ | ----- | ----------------------------------------------------------------- |
| token | string | query | **Required.** The token from the password reset link.             |
| email | string | query | **Required.** The user's email address, pre-filled from the link. |

### Middleware

- **Guest Middleware**: Access is limited to unauthenticated users to ensure that only those needing to reset their passwords can use the form.

#### View

**Name**: `Auth/ResetPassword.vue`

Displays the form for users to enter their new password and confirm it, ensuring they have the opportunity to securely update their account credentials.

**Form Submission Example**:

```typescript
const form = useForm({
    token: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.update'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
```

## `POST` Route

**Submitting the New Password**

```
POST /reset-password
```

#### Parameters

| Name                  | Type   | In   | Description                                                  |
| --------------------- | ------ | ---- | ------------------------------------------------------------ |
| token                 | string | body | **Required.** The token provided in the password reset link. |
| email                 | string | body | **Required.** The user's email address.                      |
| password              | string | body | **Required.** The new password for the account.              |
| password_confirmation | string | body | **Required.** Confirmation of the new password.              |

### Middleware

- **Guest Middleware**: Confirms that only unauthenticated users can submit new passwords.

### Response

#### Success Response

Following a successful password update, the system initiates a redirect to the login page, coupled with a confirmation message indicating the successful reset of the password. This process is managed using Laravel's routing system, which efficiently handles the redirection:

- **Password Reset Successfully**: The user is seamlessly redirected to the login page, where they are presented with a notification confirming that their password has been updated.

```php
// Redirect back with a success message after password reset
return back()->with('status', 'Password has been reset successfully.');
```

#### Error Response

In instances where the password reset fails due to various reasons, such as not meeting the validation criteria, the system redirects the user back to the reset form. This redirection is accompanied by relevant error messages, aimed at facilitating a corrected attempt at resetting the password:

- **Validation Failure**: The user is redirected back to the password reset form with specific validation error messages to correct their input.

```php
// Redirect back with validation errors
return back()
    ->withInput($request->only('email'))
    ->withErrors(['email' => trans($this->status)]);
```

- **Password Reset Link Invalid or Expired**: Alerts the user that the reset link they have used is either invalid or has expired, necessitating a new password reset request.

```php
// Redirect back with a specific error message for invalid or expired link
return back()->withErrors(['token' => 'The password reset token is invalid or has expired.']);
```

- **Mismatched Password Confirmation**: Informs the user that the password and confirmation do not match, urging a retry with matching entries.

Each error scenario is designed to provide clear guidance to the user, encouraging them to rectify their input or follow the necessary steps to secure their account.
