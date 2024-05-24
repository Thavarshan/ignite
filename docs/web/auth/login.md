# Login

The Login page is a key interface for user authentication, granting access to the application. This section provides detailed insights into the login mechanism, including routes, middleware, response handling, and view details.

## `GET` Route

**Displaying the Login Form**

```
GET /login
```

### Middleware

The login page employs middleware for secure and efficient request processing:

- **Guest Middleware**: Restricts access to unauthenticated users, redirecting authenticated users to the dashboard or another specified route.

### Response

#### Props

When accessing the login route via a `GET` request, the server provides an Inertia view with props for error handling and input retention:

- **`errors`**: Contains error messages from previous attempts, aiding in displaying feedback.
- **`oldInput`**: Retains previously entered values (e.g., email) to facilitate corrections without re-entering all data.

#### View

**Name**: `Auth/Login.vue`

This Vue component renders the login form, featuring email and password fields, and an optional "remember me" checkbox. It's designed for a seamless user experience, incorporating validation error displays and supporting redirection post-login, which may include a step for Two-Factor Authentication (TFA) verification if enabled by the user.

**Form Submission Example**:

```typescript
const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.transform(data => ({
        ...data,
        remember: form.remember ? 'on' : '',
    })).post(route('login'), {
        errorBag: 'login',
        onFinish: () => form.reset('password'),
    });
};
```

## `POST` Route

**Submitting the Login Form**

```
POST /login
```

> There are no optional parameters for both GET and POST requests.

### Parameters

| Name     | Type    | In   | Description                                                       |
| -------- | ------- | ---- | ----------------------------------------------------------------- |
| email    | string  | body | **Required.** User's email address associated with their account. |
| password | string  | body | **Required.** User's password.                                    |
| remember | boolean | body | Optional. Indicates if the user's session should be remembered.   |

### Middleware

The login `POST` route employs middleware for secure and efficient request processing:

- **Guest Middleware**: Restricts access to unauthenticated users, redirecting authenticated users to the dashboard or another specified route.
- **Throttle Middleware**: Implements rate limiting to prevent brute force attacks, allowing up to 5 attempts per minute.

### Response

#### Success Response

Upon a successful login attempt, the application determines the next step based on the user's security settings:

- **Without TFA Enabled**: The user is redirected to the dashboard. This redirection is facilitated through Laravel's routing mechanism, utilising the named route for the dashboard.

  ```php
  // Redirect to the dashboard upon successful login without TFA
  return $this->redirectHome(); // '/dashboard'
  ```

- **With TFA Enabled**: If the user has Two-Factor Authentication enabled for added security, instead of being redirected to the dashboard, they are redirected to the TFA verification screen. This step is crucial for completing the authentication process, ensuring that access is granted only after the second factor is verified.

  ```php
  // Redirect to TFA screen if the user has Two-Factor Authentication enabled
  return redirect()->route('two-factor.login');
  ```

#### Error Response

Error responses during the login process can occur for several reasons, such as invalid credentials or account issues. Common error responses include:

- **Invalid Credentials**: Redirects back to the login page with an error message about incorrect email or password. Error message accessible via `form helper` method.
	- e. g. `form.errors.email // "These credentials do not match our records."`

- **Account Locked**: Informs the user of the *locked* status of their account. The account will not be accessible until is unlocked by an **administrator** or a **client-owner** user.

- **Too Many Attempts**: Indicates that the login attempt limit has been exceeded and advises waiting before trying again.

Each error response aims to guide the user appropriately, either by correcting their input or following necessary account security protocols.
