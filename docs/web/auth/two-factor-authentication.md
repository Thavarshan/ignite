# Two-Factor Authentication (TFA)

The Two-Factor Authentication (TFA) page is an integral part of enhancing user account security, requiring a second verification step after the initial login. This section delves into the TFA process, detailing routes, middleware, response handling, and view specifics.

## `GET` Route

**Displaying the TFA Verification Form**

```
GET /two-factor-challenge
```

### Middleware

The TFA page incorporates middleware to ensure a secure and streamlined verification process:

- **Guest Middleware**: Confirms that only unauthenticated users can access TFA form.

### Response

#### Props

Accessing the TFA route via a `GET` request, the server returns an Inertia view equipped with props for managing the verification process:

- **`errors`**: Displays any error messages from failed TFA attempts, assisting in user feedback.
- **`session`**: Contains session data that might be required for the TFA challenge, such as a one-time use token or a reminder for users about TFA device registration.

#### View

**Name**: `Auth/TwoFactorChallenge.vue`

This Vue component is tasked with rendering the TFA challenge form, where users are prompted to enter their second factor, which could be a code from an authenticator app or a security token.

**Form Submission Example**:

```typescript
const form = useForm({
    code: '', // For TFA code from an authenticator app
    recovery_code: '', // Optional, for using a recovery code instead
});

const submit = () => {
    form.post(route('two-factor.login'), {
        onFinish: () => form.reset(),
    });
};
```

## `POST` Route

**Submitting the TFA Verification**

```
POST /two-factor-challenge
```

### Parameters

| Name          | Type   | In   | Description                                                                                         |
| ------------- | ------ | ---- | --------------------------------------------------------------------------------------------------- |
| code          | string | body | **Required** (if not using `recovery_code`). TFA code from the authenticator app.                   |
| recovery_code | string | body | **Required** (if not using `code`). A recovery code provided for TFA bypass in case of device loss. |

### Middleware

- **Guest Middleware**: Confirms that only unauthenticated users can submit new TFA information.

### Response

#### Success Response

After successfully verifying the second factor, users are redirected to their intended destination, typically the dashboard or home page, signifying the completion of the secure login process.

```php
// Redirect to the intended destination upon successful TFA verification
return redirect()->intended(RouteServiceProvider::HOME);
```

#### Error Response

Failures in the TFA verification process, due to incorrect codes or expired recovery codes, result in:

- **Incorrect Code**: Redirects back to the TFA page with an error message prompting the user to try again.

- **Expired Recovery Code**: Informs the user that the recovery code has expired and suggests alternative recovery options.

Each error response is designed to guide the user through resolving the verification issue or seeking alternative verification methods.
