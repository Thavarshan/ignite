# Web Views

The application employs a domain-driven design, emphasising the use of established design patterns and principles to forge a robust and scalable solution. This section delves into the web views component of the application, a pivotal element in providing a dynamic and responsive user interface.

## Overview

Web views within the application harness the combined power of Inertia.js and Vue.js. This fusion leverages the strengths of both frameworks to ensure seamless integration between the Laravel backend and the Vue.js frontend. The approach facilitates the construction of the application in a modular, component-based manner, where each view is an assembly of multiple reusable components, each dedicated to a specific aspect of the user interface.

### Key Points:

1. **Inertia.js and Vue.js Combination**:
    - **Inertia.js** serves as the bridge for achieving a single-page application (SPA) experience without a full SPA architecture. It enables server-driven applications to benefit from the interactivity of client-side JavaScript frameworks like Vue.js.
    - **Vue.js** is a progressive JavaScript framework for building user interfaces, emphasising reusable, component-based construction.

2. **Seamless Backend-Frontend Integration**:
    - Thanks to Inertia.js, seamless integration is facilitated, allowing the server to send data directly to Vue components without a traditional API layer, simplifying development.

3. **Modular, Component-Based Design**:
    - This design principle is at the core of Vue.js, fostering maintainability and code reusability. Components can be individually developed, tested, and debugged, enhancing the application's scalability.

4. **Responsive, Reusable Components**:
    - Adhering to the component-based framework principle, the approach breaks down complex UIs into manageable segments, where each component is self-contained.

In other words, this stack gives you the full power of Vue.js without the complexity of client-side routing. When using Inertia, your application's routes will respond by rendering an Inertia "page".

```php
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Show the general profile settings screen.
 */
public function show(Request $request): Response
{
    return Inertia::render('Profile/Show', [
       'sessions' => $this->sessions($request)->all(),
    ]);
}
```

## Design Philosophy

The application adopts a mobile-first and dark mode-first design philosophy for web views, ensuring responsiveness and an optimal user experience across all devices, particularly mobile. The dark mode-first strategy reduces eye strain and saves battery life, though light mode is also available for those who prefer it.

## Structure

Web views are organised in a modular fashion, consistent with the application's overall architecture. Each module, representing a specific domain, has its own set of views, which keeps the application organised and maintainable as it expands. This modular setup also enhances developer collaboration, allowing teams to focus on specific domains without overlap.

## Routing

Application routes are bifurcated into web views and API routes, with further classification into public and private routes within the web views. Public routes are accessible without authentication, serving as entry points for new or unregistered users. Conversely, private routes require authentication and enforce role-based permissions, ensuring restricted access based on user roles.

Route registration to the backend framework is predominantly done by name, facilitating easy reference in the frontend and maintaining frontend-backend sync. Routes are grouped by module-specific prefixes, aiding in organisation and maintainability.

Inertia stack includes Tighten's Ziggy library as a JavaScript alternative to the Laravel `route()` helper. You can refer to the [Ziggy usage documentation](https://github.com/tighten/ziggy#usage) for a complete guide on using this library, but some common examples can be found in application's own Vue files, including `Layouts/AppLayout.vue`:

### Example Usage of the `route` Helper Function:

```vue
// Example route usages
route('dashboard'); // /dashboard
route('users.index'); // /users
route('users.index', { page: 1 }); // /users?page=1
route('users.show', { user: 'fake-user-id' }); // /users/fake-user-id

<NavLink :href="route('dashboard')" :active="route().current('dashboard')">
Dashboard
</NavLink>
```

Note: The `route` helper function generates a URL string without validating parameters, placing the onus of validation on the caller.

## Root Endpoint

Instead of a dedicated landing page, the root endpoint directs visitors to the login page if unauthenticated, or to the dashboard if they are. This behaviour ensures a streamlined entry process to the application.

Access the root endpoint via:

```bash
curl http://ignite.test
```

## Components and Responsiveness

Designing with scalability and reusability in mind, components in the web views facilitate the easy reuse of common elements, promoting a cohesive look and feel while ensuring responsiveness across devices.

When the UI library was created, a variety of Vue components (buttons, panels, inputs, modals) were created to assist in creating UI consistency and ease of use. You are free to use or not use these components. All of these components are located within your application's `resources/js/components` directory.

Further insight may be gained into how to use these components by reviewing their usage within the application's existing pages located within your application's `resources/js/pages` directory.
