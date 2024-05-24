# Utilities Guide

## Handling Forms

### Form Submission Approach

Although traditional HTML form submissions are possible within an Inertia environment, they lead to full-page reloads and are not preferred. Inertia encourages intercepting form submissions to make requests through its API, aligning with SPA (Single Page Application) behaviors for a smoother user experience.

#### Example Using Vue 3

```vue
<script setup>
import { reactive } from 'vue';
import { Inertia } from '@inertiajs/inertia';

const form = reactive({
  first_name: '',
  last_name: '',
  email: '',
});

function submitForm() {
  Inertia.post('/users', form);
}
</script>

<template>
  <form @submit.prevent="submitForm">
    <label for="first_name">First Name:</label>
    <input id="first_name" v-model="form.first_name" />

    <label for="last_name">Last Name:</label>
    <input id="last_name" v-model="form.last_name" />

    <label for="email">Email:</label>
    <input id="email" v-model="form.email" />

    <button type="submit">Submit</button>
  </form>
</template>
```

Inertia streamlines the process, especially when handling form submissions, mirroring the simplicity found in traditional HTML forms but without the page reloads. This approach also simplifies server-side interaction, as Inertia handles the form submission and any resultant redirections naturally.

### Server-Side Validation

Inertia handles server-side validation differently than manual AJAX/fetch requests. Instead of expecting a `422` status code and updating form errors manually, Inertia expects a redirect response from the server, typically redirecting the user back to the form with validation errors automatically injected into the form state.

For detailed insights into managing and displaying validation errors with Inertia, refer to the official [Inertia.js Validation documentation](https://inertiajs.com/validation).

### Inertia Form Helper

Inertia introduces a `form` helper to minimize boilerplate code required for form submissions, streamlining operations such as post, put, patch, delete, and data transformation.

```vue
<script setup>
import { useForm } from '@inertiajs/inertia'

const form = useForm({
  email: '',
  password: '',
  remember: false,
});

function handleSubmit() {
  form.post('/login');
}
</script>

<template>
  <form @submit.prevent="handleSubmit">
    <input type="email" v-model="form.email" />
    <div v-if="form.errors.email">{{ form.errors.email }}</div>

    <input type="password" v-model="form.password" />
    <div v-if="form.errors.password">{{ form.errors.password }}</div>

    <label>
      <input type="checkbox" v-model="form.remember" /> Remember Me
    </label>

    <button type="submit" :disabled="form.processing">Login</button>
  </form>
</template>
```

The `form` helper supports typical request methods and options, including callbacks like `onSuccess` for post-submission behaviors and `transform` for data manipulation before sending to the server.

```typescript
form.post('/profile', {
  preserveScroll: true,
  onSuccess: () => form.reset('password'),
});
```

Handling file uploads, tracking submission progress, managing validation errors, and resetting form states are also simplified through the `form` helper, making form interactions intuitive and efficient within the Inertia framework.

## File Uploads with Inertia

### Automatic FormData Conversion

Inertia automatically converts request data containing files into `FormData`, facilitating `multipart/form-data` submissions necessary for file uploads. This automation ensures compatibility and ease of use when handling file inputs in your forms.

#### File Upload Example in Vue 3

```vue
<script setup>
import { useForm } from '@inertiajs/vue3';

const form = useForm({
  name: '',
  avatar: null,
});

function submit() {
  form.post('/users');
}
</script>

<template>
  <form @submit.prevent="submit">
    <input type="text" v-model="form.name" />
    <input type="file" @change="form.avatar = $event.target.files[0]" />
    <progress v-if="form.progress" :value="form.progress.percentage" max="100">
      {{ form.progress.percentage }}%
    </progress>
    <button type="submit">Submit</button>
  </form>
</template>
```

This example illustrates how to handle file uploads alongside other form data, leveraging Inertia's streamlined file upload process and its automatic handling of `FormData`.

Inertia simplifies form submissions and file uploads, maintaining SPA-like interactivity while leveraging Laravel's robust backend validation and routing capabilities, providing a seamless user experience across client and server interactions.
