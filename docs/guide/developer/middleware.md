# Middleware

Middleware on our platform acts as a crucial gatekeeper, orchestrating access control and bolstering secure, efficient operations across the system. This documentation page offers an in-depth exploration of all custom middleware components implemented within our platform, underscoring their pivotal roles in upholding the platform's integrity and operational excellence.

## `BlockJsonRequests`

The `BlockJsonRequests` middleware is designed to block all `JSON` requests for certain parts of the application where `JSON` is either not expected or could potentially introduce security risks.

#### How It Works

This middleware evaluates incoming requests to determine if they accept and expect `JSON` responses. If a request meets both criteria, it's immediately terminated with a `403 Forbidden` status, and a message is displayed indicating that `JSON` requests are not allowed. This ensures a stringent control mechanism over the types of requests processed by certain routes or areas within the application.

#### Implementation Example

```php
use App\Http\Middleware\BlockJsonRequests;

// Applying middleware to a route group or an individual route
Route::middleware([BlockJsonRequests::class])->group(function () {
    // Routes that should block JSON requests
});
```

## `BlockWebRequests`

In contrast, the `BlockWebRequests` middleware is tailored to intercept and forbid web requests, particularly targeting those that do not accept `JSON` but accept `HTML`. This is crucial for parts of the application specifically designed to handle `JSON` requests.

#### How It Works

Upon receiving a request, this middleware checks if the request does not accept `JSON` and does accept `HTML`. If these conditions are satisfied, the request is aborted with a `403 Forbidden` status, and a message is displayed stating that web requests are not allowed. This mechanism protects certain application areas from processing inappropriate web requests.

#### Implementation Example

```php
use App\Http\Middleware\BlockWebRequests;

// Assigning middleware to specific routes or route groups
Route::middleware([BlockWebRequests::class])->group(function () {
    // Routes that should exclusively accept JSON requests
});
```

`BlockJsonRequests` and `BlockWebRequests` are essential middleware classes for managing request types within your application. They ensure that your application responds securely and appropriately to incoming requests, aligning with the designed request-response dynamics.

## `XSSGuard`

The `XSSGuard` middleware offers a comprehensive solution against Cross-Site Scripting (`XSS`) attacks by cleaning all incoming request data. Utilising the `GrahamCampbell\SecurityCore\Security` package, it plays a vital role in preserving the application's integrity and security.

### Overview

XSS attacks involve injecting malicious scripts into web pages, which can exploit vulnerabilities in applications accepting user inputs. `XSSGuard` proactively neutralises such threats by sanitising request data before the application processes it.

#### Key Features

- **Automatic Cleaning**: Ensures all data received via `HTTP` requests is sanitised, allowing only safe inputs to be processed.
- **Customisable Sanitisation**: Provides options for trimming whitespace and applying custom cleaning logic, catering to specific application needs.
- **Seamless Integration**: Easily integrates into the Laravel middleware stack, enhancing security without disrupting existing functionalities.

### How It Works

`XSSGuard` intercepts incoming requests, extracting and cleaning all input values. It offers trimming and security measures to remove potentially harmful scripts or content. The sanitized inputs are then merged back into the request, ready for safe processing.

#### Implementation

1. **Dependency Injection**: Utilizes `GrahamCampbell\SecurityCore\Security` for actual input cleaning.
2. **Cleaning Logic**: Recursively sanitises inputs, bypassing certain data types (null, booleans, integers, and floats) where `XSS` is not a concern. Applies sanitisation to strings and arrays, removing malicious content.
3. **Request Modification**: Merges sanitized data back into the request, ensuring subsequent application processes handle secure inputs.

#### Usage Example

```php
// Global middleware registration in 'app/Http/Kernel.php'
protected $middleware = [
    \App\Http\Middleware\XSSGuard::class,
];

// Assigning to specific routes or groups
Route::middleware([\App\Http\Middleware\XSSGuard::class])->group(function () {
    // Routes requiring XSS protection
});
```

The `XSSGuard` middleware is an indispensable security measure against `XSS` attacks, ensuring that all incoming data is thoroughly sanitised. By integrating `XSSGuard` into your middleware stack, you take a significant step towards safeguarding your application from prevalent web security threats, thus maintaining a secure environment for users and the application itself.

## `LoadEntitySettings`

In the realm of sophisticated web applications, especially those built with Laravel, middleware not only steers the request-response lifecycle but also enriches the context of each request. The `LoadEntitySettings` middleware exemplifies this by dynamically loading settings for both the current user and their associated organisation, ensuring that each request is processed with a full understanding of the user's preferences and organisational policies.

### Overview

The `LoadEntitySettings` middleware serves a pivotal role in our application by injecting user-specific and organisation-specific settings into Laravel's service container. This process allows for seamless access to these settings throughout the application, enabling personalised user experiences and ensuring that organisation-level configurations are respected.

#### How It Works

Upon intercepting an incoming request, the middleware performs the following actions:

1. **User Verification**: Initially, it verifies if the request is made by an authenticated user. If no user is associated with the request, it proceeds to the next middleware without alteration.
2. **Loading User Settings**: If an authenticated user is identified, the middleware fetches the user's settings using the `UserSettingsRepository`. These settings are then stored in the application's service container, making them accessible across the application.
3. **Loading Organisation Settings**: Similarly, the middleware retrieves settings for the user's organisation through the `OrganisationSettingsRepository` and stores them in the service container. This ensures that any organisation-specific configurations are applied to the request processing.

#### Implementation Insight

The middleware leverages Laravel's service container and the concept of service providers to dynamically register settings instances (`UserSettings` and `OrganisationSettings`). This design pattern allows for flexibility and extensibility, enabling the application to adapt to varying user and organisation settings seamlessly.

### Practical Application

Integrating the `LoadEntitySettings` middleware into your application's middleware stack is straightforward. Typically, it is registered globally to ensure that user and organisation settings are loaded for every request. However, it can also be applied to specific routes or route groups as needed.

#### Registration Example

To apply the `LoadEntitySettings` middleware globally, you would add it to the global middleware array in your application's `kernel.php`:

```php
protected $middleware = [
    // Other global middleware...
    \App\Http\Middleware\LoadEntitySettings::class,
];
```

For applying it to specific routes or groups, you can use the `middleware` method on the route definition:

```php
Route::middleware([LoadEntitySettings::class])->group(function () {
    // Define routes that require dynamic loading of settings here
});
```

The `LoadEntitySettings` middleware is an essential component for applications that demand a high degree of personalisation and adherence to organisational policies. By dynamically loading and applying user and organisation settings, it ensures that each request is handled in a context-aware manner, paving the way for a more intuitive and responsive user experience. Integrating this middleware into your Laravel application elevates the overall sophistication of your request handling, making it both personalised and policy-compliant.

## `ValidateJsonBody`

In our Laravel application, where the robustness and reliability of data exchange are paramount, the `ValidateJsonBody` middleware plays a critical role. It's designed to scrutinize the body of incoming JSON requests for validity, ensuring that malformed JSON data is caught early in the request lifecycle. This not only enhances the security of the application but also improves the overall user experience by preventing unexpected errors during data processing.

### Purpose and Functionality

The `ValidateJsonBody` middleware is tasked with validating the JSON payload of incoming requests. It operates under the principle that early detection of data format issues can significantly reduce processing errors and improve the efficiency of request handling. Here's how it works:

1. **JSON Expectation Check**: It first checks if the incoming request expects a JSON response and is not a GET request. GET requests typically do not contain a request body, and thus, their JSON validity isn't a concern.

2. **Non-Empty Payload**: The middleware then ensures that the request's payload is not empty. An empty payload is considered valid for requests that might not require body data.

3. **JSON Validation**: Using `json_decode`, the middleware attempts to parse the request's payload. It then uses `json_last_error` to check if the decoding process resulted in any errors.

4. **Abort on Error**: If any error is detected during the JSON decoding process (indicating the JSON data is malformed), the middleware aborts the request with a 400 Bad Request status, accompanied by an error message indicating invalid JSON data.

### Implementation Example

Incorporating the `ValidateJsonBody` middleware into your application ensures that all incoming JSON requests are properly formatted before they reach your application's core logic. Here’s how you can implement it:

#### Registering the

First, you need to register the middleware within your application. This can be done in the `app/Http/Kernel.php` file. You can add it to either the global middleware stack or a specific middleware group, depending on your needs.

```php
protected $middleware = [
    // Other middleware...
    \App\Http\Middleware\ValidateJsonBody::class,
];
```

Or, for a more targeted approach, especially if you only want to validate JSON bodies for specific routes:

```php
protected $routeMiddleware = [
    // Other route middleware...
    'validate.json' => \App\Http\Middleware\ValidateJsonBody::class,
];
```

#### Applying to Routes

For routes or groups that should only accept valid JSON payloads, apply the middleware directly:

```php
Route::middleware(['validate.json'])->group(function () {
    Route::post('/api/data', [DataController::class, 'store']);
    // Additional routes that require JSON validation
});
```

### Benefits and Impact

By ensuring that all incoming JSON data is correctly formatted, the `ValidateJsonBody` middleware:

- **Enhances Security**: Malformed JSON data can sometimes be a vector for security vulnerabilities. Validating JSON integrity helps mitigate such risks.
- **Improves Data Processing**: By guaranteeing that incoming data is well-formed, it streamlines data processing and error handling in downstream application logic.
- **Boosts User Experience**: For applications that rely on client-server data exchange, ensuring that data is correctly formatted from the start prevents many common errors, leading to a smoother user experience.

The `ValidateJsonBody` middleware is an essential tool in the Laravel developer's toolkit, particularly for applications heavily reliant on JSON for data interchange. It provides a simple yet effective first line of defence against malformed JSON data, ensuring that your application can trust and efficiently process incoming requests. Integrating this middleware into your Laravel application elevates data integrity and reliability, marking a significant step toward building robust and secure web applications.

## `RedirectIfLocked`

In the security-conscious landscape of web application development, controlling access based on user status is crucial. The `RedirectIfLocked` middleware embodies this principle by ensuring that users marked as "locked" are promptly redirected and logged out, reinforcing the application's security posture and adherence to access policies.

### Purpose and Workflow

The primary objective of the `RedirectIfLocked` middleware is to intercept requests from users who have been marked as locked—a status indicating temporary or permanent suspension of access—and redirect them to a designated login or notification page. This process not only prevents locked users from accessing application resources but also provides a clear feedback loop regarding their access status.

#### How It Operates

1. **User Status Check**: Upon intercepting a request, the middleware checks if the user associated with the request is in a locked state. This is determined by invoking the `isLocked()` method on the user model.

2. **Forced Logout**: If the user is found to be locked, the middleware uses the injected `StatefulGuard` instance to log the user out, effectively invalidating their current session.

3. **Redirection**: After logging out the user, the middleware redirects them to the login page or another predetermined route. This redirection is performed with a 303 See Other status, which is appropriate for ensuring the client performs a GET request regardless of the original request's method.

### Integration into Your Laravel Application

Implementing the `RedirectIfLocked` middleware in your Laravel application is straightforward. Here’s a step-by-step guide:

#### Middleware Registration

First, register the middleware in your `app/Http/Kernel.php` file. You can add it to the global middleware stack, a middleware group, or as route-specific middleware, depending on your application's requirements.

```php
protected $routeMiddleware = [
    // Other middleware...
    'redirect.if.locked' => \App\Http\Middleware\RedirectIfLocked::class,
];
```

#### Applying Middleware to Routes

Next, apply the middleware to the routes or route groups that should enforce the locked user check. For example, to protect all routes within a group:

```php
Route::middleware(['redirect.if.locked'])->group(function () {
    // Protected routes here
});
```

### The Importance of User Experience and Security

Implementing the `RedirectIfLocked` middleware serves a dual purpose:

- **Enhanced Security**: It reinforces the application’s security by ensuring that locked users cannot access application resources, protecting sensitive information and functionalities from unauthorised access.

- **Improved User Experience**: By redirecting locked users to a login or notification page, you provide clear feedback on their account status, improving overall user experience by guiding them on how to proceed.

The `RedirectIfLocked` middleware is an essential security feature for Laravel applications, ensuring that users who are locked or have restricted access are handled appropriately. Its integration into your Laravel project not only enhances security by preventing unauthorised access but also improves user experience by providing immediate feedback on account status. Implementing such middleware is a testament to the application’s commitment to maintaining a secure and user-friendly environment.

## `AuthoriseIfAdmin`

In the world of web development, ensuring that administrative functionalities are tightly secured and accessible only to users with the appropriate permissions is a cornerstone of application security. The `AuthoriseIfAdmin` middleware in our Laravel application plays a crucial role in this context. It extends the authentication checks by adding an authorisation layer specifically designed to verify if the authenticated user has administrative privileges.

### Purpose and Functionality

The `AuthoriseIfAdmin` middleware is specifically designed to guard routes and resources that should only be accessible by users with administrative roles. It acts as a second line of defense, beyond authentication, to ensure that only users who are not just logged in but also possess the required administrative status can proceed.

#### How It Works

1. **Authentication**: Inherits and invokes the `handle` method from the `Authenticate` middleware to ensure that the user is authenticated.

2. **Authorisation Check**: After authentication, it checks if the authenticated user is marked as an administrator. This is typically determined by a method like `isAdmin()` on the `User` model.

3. **Access Control**: If the user does not have administrative privileges, the middleware aborts the request with a `403 Forbidden` response, indicating that the user is not authorised to access the specified resource.

4. **Continuation**: For users who pass both the authentication and authorisation checks, the request is allowed to proceed to the next stage in the request lifecycle.

### Implementation in Laravel

Integrating the `AuthoriseIfAdmin` middleware into your Laravel application involves a few straightforward steps aimed at ensuring that only administrators can access certain parts of your application.

#### Middleware Registration

To begin, register the middleware in your `app/Http/Kernel.php` file. You can do this by adding it to the `$routeMiddleware` array for easy application to specific routes or groups.

```php
protected $routeMiddleware = [
    // Other middleware...
    'admin' => \App\Http\Middleware\AuthoriseIfAdmin::class,
];
```

#### Applying Middleware to Routes

With the middleware registered, you can now apply it to protect your admin routes. For example, to secure a group of administrative routes:

```php
Route::middleware(['admin'])->group(function () {
    // Administrative routes here
});
```

### The Impact on Security and Usability

By deploying the `AuthoriseIfAdmin` middleware:

- **Enhanced Security**: You significantly bolster the security of your application by ensuring that only authenticated users with administrative rights can access sensitive administrative functions.

- **Clear User Feedback**: In cases where access is denied, providing a clear and concise error message helps users understand their access limitations, potentially guiding them on how to request the necessary permissions.

The `AuthoriseIfAdmin` middleware is a critical component of any Laravel application with administrative functionalities. It ensures that such functionalities are kept secure and accessible only to users with the appropriate level of authorisation, thereby protecting the application from unauthorised access and potential security breaches. Integrating this middleware into your Laravel project is a smart step towards building a secure and robust web application.

## Summary

Each middleware component plays a vital role in securing and optimising Laravel applications, from protecting against unauthorised access and potential security threats to ensuring data integrity and enhancing the user experience. Proper implementation and utilisation of these middleware components are key to developing robust, secure, and user-friendly web applications.
