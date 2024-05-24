# Find Police Force By Area

This API endpoint facilitates the query of Police forces according to the given area/location. When location coordinates or address information is provided

```
POST /api/v1/police/find
```

**Route Name**: `v1:police.find`

### Parameters

| Name                | Type   | In   | Description                                                                          |
| ------------------- | ------ | ---- | ------------------------------------------------------------------------------------ |
| address             | array  | body | **Required**. Must contain keys: `line1`, `city`, `state`, `country`, `postal_code`. |
| address.line1       | string | body | **Required**. The first line of the address.                                         |
| address.line2       | string | body | Optional. The second line of the user's address.                                     |
| address.city        | string | body | **Required**. The city of the user's address.                                        |
| address.state       | string | body | **Required**. The state of the user's address.                                       |
| address.country     | string | body | **Required**. The country of the user's address.                                     |
| address.postal_code | string | body | **Required**. The postal code of the user's address. Must be a valid UK postal code. |

### Response

```
Status: 200 OK
```

```json=
{
    "data": [
        {
            "id": 1,
            "name": "West Midlands Police",
            "slug": "west-midlands-police",
            "description": null,
            "chase_after": null,
            "retention_duration": 1095,
            "locked_at": null,
            "created_at": "2024-04-30T00:28:21.000000Z",
            "updated_at": "2024-04-30T00:28:21.000000Z",
            "media_url": "https://ui-avatars.com/api/?name=W+M+P&color=7F9CF5&background=EBF4FF",
            "media": []
        }
    ]
}
```

### Invalid Form Input

```
Status: 422 Unprocessable Entity
```

```json
{
 "message": "The address field is required. (and 5 more errors)",
 "errors": {
  "address": [
   "The address field is required."
  ],
  "address.line1": [
   "The address.line1 field is required."
  ],
  "address.city": [
   "The address.city field is required."
  ],
  "address.state": [
   "The address.state field is required."
  ],
  "address.country": [
   "The address.country field is required."
  ],
  "address.postal_code": [
   "The address.postal code field is required."
  ]
 }
}
```
