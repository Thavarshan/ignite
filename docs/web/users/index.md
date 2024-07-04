# Users

The Users Index page is a crucial interface for managing users in the application. This section provides detailed insights into the users index mechanism, including routes, middleware, response handling, and view details.

**Displaying the Users Index**

```
GET /users
```

## Parameters

| Name         | Type   | In    | Description                                                           |
| ------------ | ------ | ----- | --------------------------------------------------------------------- |
| name         | string | query | **Optional.** Filter users by similar name.                           |
| email        | string | query | **Optional.** Filter users by similar email.                          |
| domain       | string | query | **Optional.** Filter users by similar email domain, e. g. 'gmail'.    |
| organisation | string | query | **Optional.** Filter users by similar organisation `slug` value.      |
| role         | string | query | **Optional.** Filter users by role `slug` value                       |
| created_at   | string | query | **Optional.** Filter users by similar created_at date (Y-m-d) string. |

## Middleware

The users index page employs middleware for secure and efficient request processing:

- **Auth Middleware**: Ensures only authenticated users can access the users index, redirecting unauthenticated users to the login page.
- **Authorisation**: Ensure only authorised users can access the users index, redirecting unauthorised users to the dashboard or another specified route.

## Authorisation

Only users with `viewAny` permissions can access the users index. This permission is defined in the `UserPolicy` class. The `viewAny` method check for the user's role and only allows users with the role of `admin` to access the users index.

```php
/**
 * Determine whether the user can view any models.
 *
 * @param  \App\Models\User  $user
 * @return mixed
 */
public function viewAny(User $user)
{
    return $user->hasRole('admin');
}
```

## Response

### Props

When accessing the users index route via a `GET` request, the server provides an Inertia view with props for pagination and search:

- **`users`**: Contains a paginated list of users.
  - **`data`**: Contains the list of users.
  - **`links`**: Contains the pagination links.
  - **`meta`**: Contains the pagination metadata.

```json
{
   "total": 50,
   "per_page": 15,
   "current_page": 1,
   "last_page": 4,
   "first_page_url": "http://minerva.test?page=1",
   "last_page_url": "http://minerva.test?page=4",
   "next_page_url": "http://minerva.test?page=2",
   "prev_page_url": null,
   "path": "http://minerva.test",
   "from": 1,
   "to": 15,
   "data": [
        {
            "id": 6,
            "uid": "D6ZLPW92JOQZP5G74NKE8MVX",
            "firstname": "Admin",
            "middlenames": null,
            "lastname": "User",
            "email": "default.admin@ignite.com",
            "email_verified_at": null,
            "two_factor_confirmed_at": null,
            "locked_at": null,
            "current_team_id": null,
            "organisation_id": 6,
            "media_url": null,
            "created_at": "2024-02-01T14:39:13.000000Z",
            "updated_at": "2024-02-01T14:39:13.000000Z",
            "media_url": "https://ui-avatars.com/api/?name=A+U&color=7F9CF5&background=EBF4FF",
            "name": "Admin User",
            "settings": {
                "id": 12,
                "items": {
                    "notifications": {
                        "sms": {},
                        "push": {},
                        "email": {}
                    }
                },
                "subject_type": "Ignite\\Users\\Models\\User",
                "subject_id": 6,
                "created_at": "2024-02-01T14:39:13.000000Z",
                "updated_at": "2024-02-01T14:39:13.000000Z"
            }
        },
        {
            // Record...
        }
   ]
}
```

### View

**Component Name**: `Users/Index.vue`

This Vue component renders the users list, featuring a search bar, pagination controls, and a list of users with their details. It's designed for a seamless user experience, incorporating search, filtering by various attributes and pagination functionalities.

The search should be performed via the `name` filter.

**Search Example (suggestion only)**:

```typescript
const search = ref('');

const getUsers = () => {
    Inertia.get(route('users', { name: search.value }));
};

watch(search, getUsers, { immediate: true });
```

Ideally a drop down should be implemented for the filter feature.

**Filter Example (suggestion only)**:

```typescript
const filters = ref({
    name: '',
    email: '',
    domain: '',
    organisation: '',
    role: '',
    created_at: ''
});

const getUsers = () => {
    // Convert filters object to URL-friendly query parameters
    const params = new URLSearchParams(
        Object
            .entries(filters.value)
            .filter(([key, value]) => value)
    );

    Inertia.get(route('users', params));
};

watch(filters, getUsers, { immediate: true });
```
