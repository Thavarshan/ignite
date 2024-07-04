# Register

The Register page facilitates new user account creation, pivotal for accessing the application. This section provides an overview of the registration mechanism, underscored by the prerequisite of an organisational invitation for successful user registration.

## `GET` Route

**Displaying the Registration Form**

```
GET /register
```

### Parameters

| Name  | Type   | In    | Description                                                                                                           |
| ----- | ------ | ----- | --------------------------------------------------------------------------------------------------------------------- |
| email | string | query | **Optional.** User's email address associated with the user's invitation. This information is injected as a Vue prop. |

### Middleware

- **Guest Middleware**: Ensures the form is accessible only to unauthenticated users, preventing access by logged-in users.

### Response

#### Props

Upon accessing the registration route via a `GET` request, an Inertia view is served with specific props essential for the registration process:

- **`email`**: The email address from the invitation is pre-filled in the form, ensuring that registration is tied to a valid invitation.
- **`errors`**: Displays error messages from previous registration attempts.
- **`oldInput`**: Retains previously entered non-sensitive data, facilitating form corrections.

#### View

**Name**: `Auth/Register.vue`

This component displays the registration form, requiring personal details and organisational affiliation, tailored to ensure users register under an organisation they've been invited to.

**Form Submission Example**:

```typescript
const form = useForm({
    firstname: '',
    lastname: '',
    email: '', // Pre-filled from the invitation
    password: '',
    password_confirmation: '',
    organisation_id: '', // Optional and used only if information available
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
```

## `POST` Route

**Form Submission**

```
POST /register
```

### Parameters

| Name                  | Type    | In   | Description                                                                                    |
| --------------------- | ------- | ---- | ---------------------------------------------------------------------------------------------- |
| firstname             | string  | body | **Required.** User's first name.                                                               |
| lastname              | string  | body | **Required.** User's last name.                                                                |
| email                 | string  | body | **Required.** Pre-filled, invitation-specific email.                                           |
| password              | string  | body | **Required.** Account password.                                                                |
| password_confirmation | string  | body | **Required.** Confirmation of the password.                                                    |
| organisation_id       | integer | body | **Optional.** Used only information regarding the user's associated organisation is available. |

### Middleware

- **Guest Middleware**: Limits form access to unauthenticated users.
- **Throttle Middleware**: May be applied to deter spam and brute-force attacks. Allows 5 attempts within a minute.

### Response

#### Success Response

A successful registration is contingent upon verifying an existing invitation in the database for the user's email. Once verified, the application navigates the user to the next appropriate step:

- **Invitation Verified**: Post-registration, users are typically redirected to the dashboard page, facilitated through Laravel's routing, marking the completion of the registration process.

```php
// Redirect to the dashboard or welcome page upon successful registration
return redirect()->intended(RouteServiceProvider::HOME); // '/dashboard'
```

- **Additional Verification Required**: If additional steps are required post-registration, such as verifying email or additional organisational onboarding processes, the user is guided accordingly.

```php
// Redirect to additional verification or onboarding steps if necessary
return redirect()->route('verification.notice');
```

#### Error Response

Errors in the registration process, primarily due to invitation validation failures or data discrepancies, prompt specific user feedback:

- **Invalid or Missing Invitation**: Redirects back to the registration form with a message indicating the necessity of a valid invitation.

```php
// Example error for missing or invalid invitation
return back()->withErrors([
    'email' => 'An invitation for eamil [john@ignite.com] does not exist',
]);
```

- **Data Validation Errors**: For issues like invalid email formats, users are redirected back with detailed validation errors.

```php
// Redirect back with detailed validation errors
return back()->withErrors([
    'email' => 'The email field must be a valid email address.'
]);
```

##### Invitation Validation

Registration success fundamentally depends on an invitation from an organisation:

1. **Database Verification**: The user's email must correspond with an invitation record in the database.
2. **Form Accessibility via Invitation**: Access to and submission of the registration form are predicated on the user following a specific invitation link, ensuring the `email` is pre-populated and verified against the invitation.

This structured approach emphasises secure, invitation-based registration, aligning with the application’s commitment to controlled and organisation-specific access.
