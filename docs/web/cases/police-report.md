# New Case: Police Report

This area details the process of obtaining a police report for a case. This is one of the services/continuation of the "new case" process.

The user is provided with a location finder Vue component to first choose the incident location. Once the location is provided and submited, the server will return a list of police reports associated with the location. The user can then select a police force and then followed by a police report from all the available reports provided by the selected police force.

> **Note**: For "find police force by location" API, see: [Find Police Force API](/api/police/find.html).

## Police Report

**Guide the User Through the Police Report Selection Process**

```
GET /cases/new/police-report
```

### Parameters

| Name | Type   | In    | Description                                           |
| ---- | ------ | ----- | ----------------------------------------------------- |
| slug | string | param | **Required.** The slug value of the selected service. |

**Route Name**: `cases.new.police-report`

### Middleware

The index page utilises middleware to ensure secure and efficient request handling:

- **Authentication Middleware**: Confirms that only authenticated users can access the page, redirecting unauthenticated users to the login page.
- **Authorisation Middleware**: Ensures that only authorised users can view the page, redirecting unauthorised users to the dashboard or another designated route.

### Authorisation

Access to the case creation functionality is limited to users with `create` permissions, as defined in the `InstructionPolicy` class. This authorisation checks the user’s role and allows only administrators to proceed.

### Response

#### Props

When a `GET` request is made to the new case services route, the server returns an Inertia view with the following properties:

- **`options`**: A list of categories (options) related to case instructions.

```json
{
    "options": [
        {
            "id": 2,
            "name": "Crime Report",
            "slug": "crime-report",
            "types": ["instructions"],
            "description": "Access to crime reports and associated documentation.",
            "parent_id": 1,
            "meta": null,
            "created_at": "2024-04-29T22:23:12.000000Z",
            "updated_at": "2024-04-29T22:23:12.000000Z"
        },
        {
            "id": 3,
            "name": "RTC Report",
            "slug": "rtc-report",
            "types": ["instructions"],
            "description": "Access to RTC reports and associated documentation.",
            "parent_id": 1,
            "meta": null,
            "created_at": "2024-04-29T22:23:12.000000Z",
            "updated_at": "2024-04-29T22:23:12.000000Z"
        },
        ...
    ]
}
```

#### View

**Component Name**: `Cases/Create/PoliceReport.vue`
