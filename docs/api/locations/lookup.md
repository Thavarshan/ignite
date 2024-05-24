# Location Lookup

This API endpoint facilitates the lookup of similar locations based on the user's query string. When a partial or full address is provided, the system searches and returns matching address details.

```
GET /api/v1/locations/lookup
```

**Route Name**: `v1:locations.lookup`

### Parameters

| Name  | Type   | In    | Description                                                               |
| ----- | ------ | ----- | ------------------------------------------------------------------------- |
| query | string | query | **Required**. The partial or full address query for looking up locations. |

### Request Example

Here's an example of making a request to the location lookup endpoint:

```bash
curl --location 'http://minerva.test/api/v1/locations/lookup?query=pilkington%20avenue%2C%20birmingham' \
--header 'Accept: application/json' \
--header 'Content-Type: application/json'
```

### Middleware

The index page utilises middleware to ensure secure and efficient request handling:

- **Authentication Middleware**: Confirms that only authenticated users can access the page, redirecting unauthenticated users to the login page.

> All authenticated users are allowed to access this endpoint.

### Response

```
Status: 200 OK
```

```json
{
    "data": [
        {
            "line1": "Pilkington Avenue",
            "line2": "Maney",
            "city": "Birmingham",
            "state": "England",
            "country": "United Kingdom",
            "postal_code": "B72 1LA"
        },
        {
            "line1": "Pilkington Avenue",
            "line2": "Maney",
            "city": "Birmingham",
            "state": "England",
            "country": "United Kingdom",
            "postal_code": "B72 1LH"
        }
    ]
}
```

### Invalid Query

If the query parameter is missing or invalid, the server responds with an error.

```
Status: 422 Unprocessable Entity
```

```json
{
    "message": "The provided location details are insufficient to determine a location. Try providing a valid postcode."
}
```
