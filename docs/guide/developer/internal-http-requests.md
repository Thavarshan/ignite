# Managing Internal HTTP Requests

In modern web applications, especially those developed with Laravel, efficiently managing internal HTTP requests is crucial for seamless functionality. This involves not only segregating API and web-facing routes but also adopting best practices for internal requests. Specifically, leveraging middleware for route protection and utilising helper functions like `apiRoute` and `route` from `ziggy-js` are key strategies. The latter is particularly useful when making web requests with `Inertia.js` or `Axios`, providing a robust solution for navigating Laravel's routing in JavaScript.

## Middleware for Route Protection

The application architecture differentiates between bot API routes, which handle JSON requests and responses, and web-facing routes, designed for user interactions through non-JSON requests that return HTML or text. To protect these distinct routes:

- **BlockJsonRequests Middleware**: Prevents JSON requests from accessing web-facing routes, ensuring that these routes only process user-based interactions.
- **BlockWebRequests Middleware**: Blocks non-JSON, web-based requests from reaching API routes, which are intended strictly for JSON interactions.

These middleware ensure that each type of route processes only the appropriate request formats, maintaining the application's security and functional integrity.

## Utilising Helper Functions for Requests

### `apiRoute` for API Requests

The `apiRoute` helper function is essential for constructing URLs for API routes, ensuring internal requests are accurately directed. This function simplifies making requests to API endpoints, enhancing code readability and maintainability.

#### Example: Fetching a QR Code with `Axios`

```typescript
import axios from 'axios';
import { apiRoute } from '@/lib/utils/route';

const showQrCode = () => {
    return axios.get(apiRoute('two-factor.qr-code'))
        .then(response => {
            // Update state with QR code SVG
            qrCode.value = response.data.svg;
        })
        .catch(error => {
            console.error('Error fetching QR Code:', error);
        });
};
```

### `route` from `ziggy-js` for Web Requests

For web requests, particularly when using Inertia.js or Axios in a Laravel application, the `route` function from `ziggy-js` is invaluable. It allows for the generation of URLs based on Laravel's named routes, enabling a seamless integration between Laravel's backend routing and frontend JavaScript frameworks.

#### Example: Navigating with Inertia.js

```javascript
import { Inertia } from '@inertiajs/inertia';
import { route } from 'ziggy-js';

const navigateToProfile = () => {
    Inertia.visit(route('user.profile'));
};
```

This example demonstrates how to navigate to a user's profile page using Inertia.js, utilizing the `route` function to generate the URL based on a named route in Laravel.

#### Example: Sending a Web Request with Axios

```javascript
import axios from 'axios';
import { route } from 'ziggy-js';

const updateUser = (userId, userData) => {
    axios.post(route('user.update', userId), userData)
        .then(response => {
            console.log('User updated successfully', response.data);
        })
        .catch(error => {
            console.error('Error updating user:', error);
        });
};
```

In this scenario, `route` is used to dynamically generate the URL for an Axios POST request to update user data, illustrating the flexibility and power of combining `ziggy-js` with Axios for web requests in a Laravel application.

## Conclusion

Adopting middleware for route protection and utilizing helper functions like `apiRoute` and `route` are best practices that enhance the security, maintainability, and functionality of web applications. These strategies enable clear separation of API and web-facing routes, while simplifying the construction of internal requests, ensuring applications are robust and user-friendly.