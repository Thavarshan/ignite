# Authentication

While the API provides multiple authentication methods, we strongly recommend using API tokens for production applications. The other methods provided are intended to be used for internal applications or testing.

## Basic Authentication

This API allows for the user to login to the application through username and password.

```
POST /api/v1/login
```

### Parameters

| Name     | Type   | In   | Description                                                            |
| -------- | ------ | ---- | ---------------------------------------------------------------------- |
| email    | string | body | **Required**. The email address the user's account is associated with. |
| password | string | body | **Required**. The password of the user.                                |

### Code Samples

```bash
curl --location --request POST 'http://minerva.test/api/v1/login' \
--header 'Accept: application/json' \
--header 'Content-Type: application/json' \
--data-raw '{
    "email": "client.staff@ignite.com",
    "password": "password"
}'
```

### Response

```
Status: 200 OK
```

```json=
{
    "id": 5,
    "uid": "7YLRGV24KE3YE58W9XJNO6MZ",
    "firstname": "Client",
    "middlenames": null,
    "lastname": "Staff",
    "email": "client.staff@ignite.com",
    "email_verified_at": null,
    "two_factor_confirmed_at": null,
    "locked_at": null,
    "current_team_id": null,
    "organisation_id": 2,
    "media_url": null,
    "created_at": "2024-01-31T20:16:06.000000Z",
    "updated_at": "2024-01-31T20:16:06.000000Z",
    "media_url": "https://ui-avatars.com/api/?name=C+S&color=7F9CF5&background=EBF4FF",
    "name": "Client Staff",
    "settings": {
        "id": 7,
        "items": {
            "notifications": {
                "sms": [],
                "push": [],
                "email": []
            }
        },
        "subject_type": "Ignite\\Users\\Models\\User",
        "subject_id": 5,
        "created_at": "2024-01-31T20:16:06.000000Z",
        "updated_at": "2024-01-31T20:16:06.000000Z"
    }
}
```

### Invalid Credentials

```
Status: 422 Unprocessable Entity
```

```json
{
    "message": "The given data was invalid.",
    "errors": {
        "email": [
            "These credentials do not match our records."
        ]
    }
}
```

### Account Locked

```
Status: 422 Unprocessable Entity
```

```json
{

 "message": "User account [client.staff@ignite.com] is locked."

}
```

## OAuth Authentication

OAuth tokens include personal access tokens, and/or internal access tokens. These token may or may not have an expiration time and enable the user to check the last access time or revoke access at any time.

```bash
curl -v -H "Authorization: Bearer TOKEN" http://minerva.test/api/v1
```

### Via Personal Access Token

Personal Access Token works the same way JWT token does except they may or may not have an expiration time.

To generate a Personal Access Token you may run the following shell command in the base path of the API codebase.

```bash
php artisan token:personal {email} {token-name}
```

You may use this token to access **specific** auth protected routes in the API by including the token in the `Authorization` header of the request.

### Via Internal Access Token (currently unavailable)

Internal Access Token is pre-shared tokens and is used mostly for internal application within MacroActive.

To generate an Internal Access Token you may run the following shell command in the base path of the API codebase.

```bash
php artisan token:internal {token-name}
```

You may use this token to access **specific** auth protected routes in the API by including the token in the `Authorization` header of the request.

## Unauthenticate

Any authenticated requests made using API tokens do not require to be unauthenticated. But the token may be invalidated

## Get Currently Authenticated User

This endpoint can be used to retrieve information about the currently authenticated user. This endpoint can only be accessed through **JWT** token.

```
GET /api/v1/user
```

### Parameters

| Name          | Type   | In     | Description                                            |
| ------------- | ------ | ------ | ------------------------------------------------------ |
| Authorisation | string | header | **Required**. The API token of the authenticated user. |

### Response

```
Status: 200 OK
```

```json
{
    "id": 1,
    "uid": "GNEDRZKO2J5XL56X84MVY7LP",
    "firstname": "Admin",
    "middlenames": null,
    "lastname": "User",
    "email": "default.admin@ignite.com",
    "email_verified_at": null,
    "two_factor_confirmed_at": null,
    "locked_at": null,
    "current_team_id": null,
    "organisation_id": 1,
    "media_url": null,
    "created_at": "2024-01-31T20:16:06.000000Z",
    "updated_at": "2024-01-31T20:16:06.000000Z",
    "media_url": "https://ui-avatars.com/api/?name=A+U&color=7F9CF5&background=EBF4FF",
    "name": "Admin User",
    "settings": {
        "id": 2,
        "items": {
            "notifications": {
                "sms": [],
                "push": [],
                "email": []
            }
        },
        "subject_type": "Ignite\\Users\\Models\\User",
        "subject_id": 1,
        "created_at": "2024-01-31T20:16:06.000000Z",
        "updated_at": "2024-01-31T20:16:06.000000Z"
    },
    "roles": [
        {
            "id": 1,
            "name": "Admin",
            "slug": "admin",
            "description": null,
            "created_at": "2024-01-31T20:16:05.000000Z",
            "updated_at": "2024-01-31T20:16:05.000000Z",
            "pivot": {
                "user_id": 1,
                "role_id": 1
            },
            "permissions": [
                {
                    "id": 1,
                    "name": "permissions:*",
                    "slug": "permissions",
                    "created_at": "2024-01-31T20:16:04.000000Z",
                    "updated_at": "2024-01-31T20:16:04.000000Z",
                    "pivot": {
                        "role_id": 1,
                        "permission_id": 1,
                        "created_at": "2024-01-31T20:16:05.000000Z",
                        "updated_at": "2024-01-31T20:16:05.000000Z"
                    }
                },
                {
                    "id": 3,
                    "name": "roles:*",
                    "slug": "roles",
                    "created_at": "2024-01-31T20:16:04.000000Z",
                    "updated_at": "2024-01-31T20:16:04.000000Z",
                    "pivot": {
                        "role_id": 1,
                        "permission_id": 3,
                        "created_at": "2024-01-31T20:16:05.000000Z",
                        "updated_at": "2024-01-31T20:16:05.000000Z"
                    }
                },
                {
                    "id": 7,
                    "name": "settings:*",
                    "slug": "settings",
                    "created_at": "2024-01-31T20:16:04.000000Z",
                    "updated_at": "2024-01-31T20:16:04.000000Z",
                    "pivot": {
                        "role_id": 1,
                        "permission_id": 7,
                        "created_at": "2024-01-31T20:16:05.000000Z",
                        "updated_at": "2024-01-31T20:16:05.000000Z"
                    }
                },
                {
                    "id": 12,
                    "name": "organisations:*",
                    "slug": "organisations",
                    "created_at": "2024-01-31T20:16:04.000000Z",
                    "updated_at": "2024-01-31T20:16:04.000000Z",
                    "pivot": {
                        "role_id": 1,
                        "permission_id": 12,
                        "created_at": "2024-01-31T20:16:05.000000Z",
                        "updated_at": "2024-01-31T20:16:05.000000Z"
                    }
                },
                {
                    "id": 17,
                    "name": "teams:*",
                    "slug": "teams",
                    "created_at": "2024-01-31T20:16:04.000000Z",
                    "updated_at": "2024-01-31T20:16:04.000000Z",
                    "pivot": {
                        "role_id": 1,
                        "permission_id": 17,
                        "created_at": "2024-01-31T20:16:05.000000Z",
                        "updated_at": "2024-01-31T20:16:05.000000Z"
                    }
                },
                {
                    "id": 22,
                    "name": "invitations:*",
                    "slug": "invitations",
                    "created_at": "2024-01-31T20:16:04.000000Z",
                    "updated_at": "2024-01-31T20:16:04.000000Z",
                    "pivot": {
                        "role_id": 1,
                        "permission_id": 22,
                        "created_at": "2024-01-31T20:16:05.000000Z",
                        "updated_at": "2024-01-31T20:16:05.000000Z"
                    }
                },
                {
                    "id": 27,
                    "name": "users:*",
                    "slug": "users",
                    "created_at": "2024-01-31T20:16:04.000000Z",
                    "updated_at": "2024-01-31T20:16:04.000000Z",
                    "pivot": {
                        "role_id": 1,
                        "permission_id": 27,
                        "created_at": "2024-01-31T20:16:05.000000Z",
                        "updated_at": "2024-01-31T20:16:05.000000Z"
                    }
                },
                {
                    "id": 32,
                    "name": "locations:*",
                    "slug": "locations",
                    "created_at": "2024-01-31T20:16:05.000000Z",
                    "updated_at": "2024-01-31T20:16:05.000000Z",
                    "pivot": {
                        "role_id": 1,
                        "permission_id": 32,
                        "created_at": "2024-01-31T20:16:05.000000Z",
                        "updated_at": "2024-01-31T20:16:05.000000Z"
                    }
                },
                {
                    "id": 37,
                    "name": "tasks:*",
                    "slug": "tasks",
                    "created_at": "2024-01-31T20:16:05.000000Z",
                    "updated_at": "2024-01-31T20:16:05.000000Z",
                    "pivot": {
                        "role_id": 1,
                        "permission_id": 37,
                        "created_at": "2024-01-31T20:16:05.000000Z",
                        "updated_at": "2024-01-31T20:16:05.000000Z"
                    }
                },
                {
                    "id": 42,
                    "name": "police:*",
                    "slug": "police",
                    "created_at": "2024-01-31T20:16:05.000000Z",
                    "updated_at": "2024-01-31T20:16:05.000000Z",
                    "pivot": {
                        "role_id": 1,
                        "permission_id": 42,
                        "created_at": "2024-01-31T20:16:05.000000Z",
                        "updated_at": "2024-01-31T20:16:05.000000Z"
                    }
                },
                {
                    "id": 47,
                    "name": "categories:*",
                    "slug": "categories",
                    "created_at": "2024-01-31T20:16:05.000000Z",
                    "updated_at": "2024-01-31T20:16:05.000000Z",
                    "pivot": {
                        "role_id": 1,
                        "permission_id": 47,
                        "created_at": "2024-01-31T20:16:05.000000Z",
                        "updated_at": "2024-01-31T20:16:05.000000Z"
                    }
                }
            ]
        }
    ],
    "teams": [
        {
            "id": 2,
            "name": "Admins",
            "user_id": 1,
            "organisation_id": 1,
            "personal_team": false,
            "slug": "admins",
            "created_at": "2024-01-31T20:16:05.000000Z",
            "updated_at": "2024-01-31T20:16:06.000000Z",
            "pivot": {
                "user_id": 1,
                "team_id": 2
            }
        }
    ],
    "organisation": {
        "id": 1,
        "uid": "GNEDRZKO2J5XL56X84MVY7LP",
        "name": "Ignite",
        "email": "enquiries@ignite.com",
        "phone": "+441217739584",
        "slug": "ignite",
        "description": null,
        "website": "https://www.ignite.com",
        "media_url": null,
        "locked_at": null,
        "created_at": "2024-01-31T20:16:05.000000Z",
        "updated_at": "2024-01-31T20:16:05.000000Z",
        "settings": {
            "id": 1,
            "items": {
                "admins": {
                    "limit": 1,
                    "enabled": false
                },
                "features": {
                    "ip_authorisation": {
                        "ranges": [
                            "192.168.1.1-192.168.1.255",
                            "0.0.0.0-127.0.0.1"
                        ],
                        "enabled": false
                    }
                },
                "management": {
                    "limit": 10,
                    "enabled": true
                }
            },
            "subject_type": "Ignite\\Organisations\\Models\\Organisation",
            "subject_id": 1,
            "created_at": "2024-01-31T20:16:05.000000Z",
            "updated_at": "2024-01-31T20:16:05.000000Z"
        }
    }
}
```

### Invalid API Token

```
Status: 401 Unauthorized
```

```json
{

 "message": "Unauthenticated."

}
```
