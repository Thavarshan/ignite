# Forgot Password

The Password Reset process is a critical security feature, allowing users to regain access to their accounts by resetting their password. This section outlines the steps and mechanisms involved in the password reset process, ensuring users can securely manage their account access.

## `GET` Route

**Displaying the Password Reset Request Form**

```
GET /forgot-password
```

### Parameters

None required for accessing the password reset request form.

### Middleware

- **Guest Middleware**: Restricts access to the form to unauthenticated users, ensuring that logged-in users cannot request a password reset.

### Props

Upon accessing the password reset request route via a `GET` request, an Inertia view is returned with props for managing error messages:

- **`errors`**: Displays any error messages from previous password reset requests.
- **`status`**: Optionally shows success messages after a reset email has been successfully sent.

#### View

**Name**: `Auth/ForgotPassword.vue`

This component displays the form where users can submit their email address to receive a password reset link.

**Form Submission Example**:

```typescript
const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'), {
        onFinish: () => form.reset('email'),
    });
};
```

## `POST` Route

**Submitting the Password Reset Request**

```
POST /forgot-password
```

### Parameters

| Name  | Type   | In   | Description                                                         |
| ----- | ------ | ---- | ------------------------------------------------------------------- |
| email | string | body | **Required.** The email address associated with the user's account. |

### Middleware

- **Guest Middleware**: Ensures only unauthenticated users can make password reset requests.
- **Throttle Middleware**: Applies to prevent spam and brute-force attacks on the password reset request form.

### Response

#### Success Response

After the user successfully submits the password reset link request, the application processes this request by sending an email containing the password reset link. Subsequently, the user is redirected back to the login page, where a notification confirms the dispatch of the reset link:

- **Email Sent**: A confirmation message is displayed on the login page, informing the user that an email with the password reset link has been sent.

```php
// Redirect to the login page with a success message after sending the reset link
return back()->with('status', 'We have emailed your password reset link!');
```

#### Error Response

In cases where the password reset link request encounters issues, such as submission of an email address not recognised by the system, the application ensures the user is directed back to the reset request form. This redirection includes pertinent error messages to aid in rectifying the issue:

- **Email Not Found**: If the provided email address is not associated with any account in the database, the user is redirected back to the request form with a clear indication of the error, allowing them to correct the email or take further action.

```php
return back()
    ->withInput($request->only('email'))
    ->withErrors(['email' => trans($this->status)]);
```

Each step of the error response process is meticulously designed to offer constructive feedback to the user, guiding them through the necessary steps to either correct their information or understand the nature of the issue encountered.
