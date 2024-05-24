# User Profile

The User Profile page in a Laravel Jetstream application is a central hub for users to view and update their personal information, security settings, and manage other account-specific details. This section of the documentation provides an overview of the user profile functionalities, including routes, middleware, response handling, and view details.

## `GET` Route

**Accessing the User Profile Page**

```
GET /user/profile
```

### Middleware

The User Profile page leverages middleware to ensure secure and authorized access:

- **Auth Middleware**: Ensures that only authenticated users can access the profile page, redirecting unauthenticated users to the login page.

### Response

#### Props

Upon accessing the User Profile route via a `GET` request, the server renders an Inertia view with props necessary for displaying and managing the user profile:

- **`user`**: Contains the current user's information, such as name, email, and profile photo URL.
- **`errors`**: Displays any validation or update errors that occur when the user attempts to update their profile.
- **`status`**: Optionally shows success messages after profile updates have been successfully saved.

#### View

**Name**: `Profile/Show.vue`

This Vue component is responsible for displaying the user's profile information and providing forms for updating personal details, password, and two-factor authentication settings. It's designed to offer a comprehensive and intuitive interface for account management.

