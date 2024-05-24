# Create Case

The process for submitting a new case is straightforward and varies depending on the chosen service. A guided wizard assists the user in gathering all required information for the selected service. After submission, the user is directed to the case details page, where they can review and modify the case details as needed. There may be a delay in displaying some case information as it undergoes system processing. This delay allows the system to identify and flag any incorrect information, providing the user with an opportunity to correct it later.

```
POST /cases
```

**Route Name**: `cases.store`

### Parameters

| Name             | Type    | In   | Description                                                               |
| ---------------- | ------- | ---- | ------------------------------------------------------------------------- |
| organisation_id  | integer | body | **Required**. Must be an existing organisation ID.                        |
| team_id          | integer | body | **Required**. Must be an existing team ID.                                |
| police_id        | integer | body | Optional. If present, must be an existing police ID.                      |
| reports          | array   | body | **Required** if `police_id` is present. Must be an array of report IDs.   |
| reports.*        | integer | body | **Required**. Each element must be an existing report ID.                 |
| client_reference | string  | body | **Required**. A client reference .                                        |
| description      | string  | body | Optional. A description.                                                  |
| locked           | boolean | body | Optional. A boolean indicating if the resource is locked.                 |
| categories       | array   | body | **Required**. Must be an array of category IDs.                           |
| categories.*     | integer | body | **Required**. Each element must be an existing category ID.               |
| data             | array   | body | Optional. An array of data.                                               |
| queries          | array   | body | Optional. An array of queries.                                            |
| queries.type     | string  | body | Required if `queries` is present. Must be one of the allowed query types. |

### Middleware

The index page utilises middleware to ensure secure and efficient request handling:

- **Authentication Middleware**: Confirms that only authenticated users can access the page, redirecting unauthenticated users to the login page.
- **Authorisation Middleware**: Ensures that only authorised users can view the page, redirecting unauthorised users to the dashboard or another designated route.

### Authorisation

Access to the case creation functionality is limited to users with `create` permissions, as defined in the `InstructionPolicy` class. This authorisation checks the user’s role and allows only administrators to proceed.

### Response

```
Status: 200 OK
```

```json=
{
    "organisation_id": 1,
    "police_id": 1,
    "client_reference": "test-ref",
    "description": "This is a description",
    "data": [ // The data will be organised in groups
        {
            "Insurers Information": {
                "key": "police-reference-number",
                "value": "Deserunt asperiores maiores quo ut saepe.",
                "type": "reference"
            },
            {
                "key": "insured-name",
                "value": "Ea quos rerum sunt aut fugiat nostrum ut voluptatem.",
                "type": "person"
            }
        },
        {
            "Incident Details": {
                "key": "incident-date",
                "value": "1993-07-27",
                "type": "date"
            },
            {
                "key": "incident-time",
                "value": "06:49:44",
                "type": "time",
                "errors": [
                    "The incident time field is required." // In case a data attribute is invalid
                ]
            },
            {
                "key": "incident-location",
                "value": {
                    "line1": "643 Kemmer Trafficway",
                    "line2": "Apt. 654",
                    "city": "Wizaview",
                    "state": "Virginia",
                    "postal_code": "81756-9076",
                    "country": "UK"
                },
                "type": "location"
            },
        }
    ],
    "user_id": 1,
    "updated_at": "2024-04-30T09:01:38.000000Z",
    "created_at": "2024-04-30T09:01:38.000000Z",
    "id": 4,
    "uid": "NW-CASE-L74ZK4G",
    "current_team_id": 1,
    "current_team": {
        "id": 1,
        "name": "Staff",
        "user_id": null,
        "organisation_id": 1,
        "personal_team": false,
        "slug": "staff-1",
        "created_at": "2024-04-30T01:59:41.000000Z",
        "updated_at": "2024-04-30T01:59:41.000000Z"
    }
}
```

### Invalid Form Input

```
Status: 422 Unprocessable Entity
```

```json
{
    "message": "The organisation id field is required. (and 5 more errors)",
    "errors": {
        "organisation_id": [
            "The organisation id field is required."
        ],
        "team_id": [
            "The team id field is required."
        ],
        "reports": [
            "The reports field is required."
        ],
        "client_reference": [
            "The client reference field is required."
        ],
        "categories": [
            "The categories field is required."
        ],
        "user_id": [
            "The user id field is required."
        ]
    }
}
```
