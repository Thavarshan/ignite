# New Case

## New Case Creation Process

The creation of a new case involves a multi-step process, where specific information related to the case type is gathered through a series of pages and wizards.

### Initial Step: Selecting a Service

Initially, the user is presented with a page that lists all available services required to initiate a case. As of April 30th, 2024, the available services are:

- Police Report
- Profile Finder (currently unavailable)

Upon selecting a desired service, the user is redirected to a service-specific page. Here, they can select various options to proceed with the case creation process using a specialised wizard UI. For instance, selecting "Police Report" directs the user to the endpoint `cases.new.{service}`, where `{service}` is the placeholder for the previously chosen service.

### Final Step: Wizard

Depending on the type of service chosen on the previous page, the user is presented with a choice of options and guided through a wizard to gather all necessary information required to create the actual case.

## Case Services

**Listing All Available Services**

```
GET /cases/new
```

**Route Name**: `cases.new`

### Middleware

The index page utilises middleware to ensure secure and efficient request handling:

- **Authentication Middleware**: Confirms that only authenticated users can access the page, redirecting unauthenticated users to the login page.
- **Authorisation Middleware**: Ensures that only authorised users can view the page, redirecting unauthorised users to the dashboard or another designated route.

### Authorisation

Access to the case creation functionality is limited to users with `create` permissions, as defined in the `InstructionPolicy` class. This authorisation checks the user’s role and allows only administrators to proceed.

```php
/**
* Determines if the user can create models.
*
* @param \NetWatch\Users\Models\User $user
* @return bool
*/
public function create(User $user): bool
{
    // Where $user is the currently authenticated user.
    return $user->hasPermissionTo(
        $this->do(Action::CREATE, on: Resource::INSTRUCTIONS)
    );
}
```

### Response

#### Props

When a `GET` request is made to the new case services route, the server returns an Inertia view with the following properties:

- **`services`**: A list of categories (services) related to case instructions.

```json
{
 "services": [
  {
   "id": 1,
   "name": "Police Report",
   "slug": "police-report",
   "types": ["instructions"],
   "description": "Access to police reports and associated documentation.",
   "parent_id": null,
   "meta": null,
   "created_at": "2024-04-29T22:23:12.000000Z",
   "updated_at": "2024-04-29T22:23:12.000000Z"
  },
  ...
 ]
}
```

#### View

**Component Name**: `Cases/Create/Index.vue`

This Vue component displays a list of services, each represented by a card detailing the service name, description, and a link to the service-specific page. For example, the "Police Report" service card includes a link that redirects to `cases.new.police-report`, using the `slug` attribute of the service.

## Case Service Wizard

**Guiding the User Through the Case Creation Process**

```
GET /cases/new/{category_slug}
```

### Parameters

| Name | Type   | In    | Description                                           |
| ---- | ------ | ----- | ----------------------------------------------------- |
| slug | string | param | **Required.** The slug value of the selected service. |

> The same **authorization** and **middleware** as the `cases.new` route apply to this route.

### Response

#### Props

When a `GET` request is made to the service-specific route, the server returns an Inertia view with the following properties:

- **`options`**: A list of sub-categories (options) related to the chosen service.

```json
{
 "options": [
  {
   "id": 2,
   "name": "Crime Report",
   "slug": "crime-report",
   "types": ["reports"],
   "description": "Access to crime reports and associated documentation.",
   "parent_id": 1,
   "meta": null,
   "created_at": "2024-04-29T22:23:12.000000Z",
   "updated_at": "2024-04-29T22:23:12.000000Z"
  },
  ...
 ]
}
```

> Server only provides `options` props if the service requires it.
>
#### View

**Component Name**: `Cases/Create/{ServiceName}.vue`
