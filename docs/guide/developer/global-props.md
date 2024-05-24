# Global Properties

This documentation provides a comprehensive guide on sharing server-side data globally with the client-side in a Laravel application using Inertia.js, and how to access these shared properties within Vue.js components.

## Overview

Leveraging `HandleInertiaRequests` middleware, Laravel can share global properties with Vue.js frontend components via Inertia.js. This facilitates dynamic data access and manipulation, enhancing the user experience by providing a reactive interface to server-side resources.

## Setting Up Global Properties

### HandleInertiaRequests Middleware

The `HandleInertiaRequests` middleware is central to defining which data are shared globally. Properties such as user authentication details, session flash messages, application routing, feature flags, and error messages can be specified here for global access.

**Key Shared Properties:**

- **Flash Messages** (`flash`): Temporary session messages for user feedback.
- **Authenticated User** (`auth.user`): Information about the logged-in user.
- **Error Bags** (`errorBags`): Validation error messages organized by form.
- **Ziggy Route Configuration** (`ziggy`): Enables the use of Laravel routes in JavaScript.
- **Feature Flags** (`features`): Dynamically control feature availability.

## Accessing Shared Properties in Vue.js

Inertia.js makes the shared properties accessible within Vue components, offering different methods for Vue 2 and Vue 3.

### Vue 3: Using `usePage` Composable

For Vue 3, the `usePage` composable from `@inertiajs/inertia-vue3` is used to access shared properties reactively.

```javascript
import { usePage } from '@inertiajs/inertia-vue3';

export default {
  setup() {
    const { props } = usePage();
    return {
      user: props.auth.user,
    };
  },
};
```

### Vue 2: Accessing `$page.props`

In Vue 2, shared properties are directly accessible via the `$page.props` object within the template.

```html
<template>
  <div>Hello, {{ $page.props.auth.user.name }}</div>
</template>
```

### Practical Examples

#### Displaying Flash Messages

```html
<template>
  <div v-if="$page.props.flash.message" class="alert">
    {{ $page.props.flash.message }}
  </div>
</template>
```

#### Conditional Rendering Based on Feature Flags

```html
<template>
  <div v-if="$page.props.features.newFeature">
    <!-- New Feature Component -->
  </div>
</template>
```

## Best Practices and Tips

- **Reactivity**: Changes to shared properties are reactive, ensuring the UI updates automatically to reflect changes.
- **Clear Property Access**: Use deconstruction for clarity when accessing multiple shared properties, especially in complex components.
- **Consistent Documentation**: Keep your project documentation up-to-date with the latest shared properties and access methods.

## Conclusion

Sharing global properties from Laravel to Vue.js components through Inertia.js provides a powerful pattern for building dynamic and interactive web applications. By following the guidelines and examples provided in this documentation, developers can efficiently implement and utilize this feature in their projects.

For further exploration and advanced topics, visit the [official Inertia.js documentation](https://inertiajs.com/).
