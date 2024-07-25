# API Reference

The Ignite API is designed with a focus on REST principles. It features predictable, resource-oriented URLs, accepts form-encoded request bodies, provides JSON-encoded responses, and employs standard HTTP response codes, authentication methods, and verbs.

Bulk updates are not supported by the Ignite API. Each request allows you to work on only one object at a time.

The Ignite API is tailored to individual accounts as we continually introduce new versions and customise functionality.

The below describes the resources that make up the official Ignite REST API. If you have any problems or requests, please contact [support](https://ignite.slack.com/archives/C025R32Q82K).

## Current Version

By default, all `JSON`/`XHR` requests to `http://ignite.test/api/v1` receive the `v1` version of the REST API. We encourage you to explicitly request JSON content via the `Accept` and `Content-Type` header.

```
Accept: application/json
Content-Type: application/json
```

## Schema

All API access is over HTTPS and accessed from <http://ignite.test/api/v1>. All data is sent and received as JSON.

```bash
curl -I --location http://ignite.test/api/v1 \
--header 'Content-Type: application/json' \
--header 'Accept: application/json'

HTTP/1.1 200 OK
Server: nginx/1.25.3
Content-Type: application/json
Connection: keep-alive
Vary: Accept-Encoding
X-Xdebug-Profile-Filename: /var/tmp/cachegrind.out.16617.gz
Cache-Control: no-cache, private
Date: Wed, 31 Jan 2024 18:27:21 GMT
X-RateLimit-Limit: 60
X-RateLimit-Remaining: 57
Access-Control-Allow-Origin: *
Permissions-Policy: autoplay=(self), camera=(), encrypted-media=(self), fullscreen=(), geolocation=(self), gyroscope=(self), magnetometer=(), microphone=(), midi=(), payment=(), sync-xhr=(self), usb=()
X-Content-Type-Options: nosniff
Expect-CT: enforce, max-age=30
Strict-Transport-Security: max-age=31536000; includeSubDomains
X-XSS-Protection: 1; mode=block
Referrer-Policy: no-referrer-when-downgrade
```

Blank fields are not included as null instead will be omitted.

All timestamps return in UTC time, ISO 8601 format:

```
YYYY-MM-DDTHH:MM:SSZ
```

## Authentication

The Ignite API employs Laravel Sanctum for secure API authentication. Requests that require authentication and lack proper credentials will result in a `401 Unauthorized` response, indicating the necessity for valid authorisation credentials. However, when requests are made via `Inertia.js` from the same origin domain, **Laravel Sanctum** can leverage session-based authentication, which might not require explicit API tokens. In such cases, as long as the session is valid and the user is authenticated, Sanctum will allow access to protected routes without the need for a separate token in the request header.
### Sanctum Token (sent in header)

When making requests to the Ignite API, you must include a Sanctum token in the request headers for authentication. This token ensures that the request is authorised. You can obtain a Sanctum token by logging in through the application's designated authentication endpoint. Once you have the token, include it in your API requests as follows:

```bash
curl -H 'Authorization: Bearer YOUR_SANCTUM_TOKEN' http://ignite.test/api/v1/users"
```

### Failed login limit

Authenticating with invalid credentials will return `401 Unauthorized`:

```bash
curl -I http://ignite.test/api/v1/users \
-H 'Content-Type: application/json' \
-H 'Accept: application/json'

HTTP/1.1 401 Unauthorized
Server: nginx/1.25.3
Content-Type: application/json
Connection: keep-alive
X-Xdebug-Profile-Filename: /var/tmp/cachegrind.out.408.gz
Cache-Control: no-cache, private
Date: Wed, 31 Jan 2024 19:13:51 GMT
Access-Control-Allow-Origin: *
Permissions-Policy: autoplay=(self), camera=(), encrypted-media=(self), fullscreen=(), geolocation=(self), gyroscope=(self), magnetometer=(), microphone=(), midi=(), payment=(), sync-xhr=(self), usb=()
X-Content-Type-Options: nosniff
Expect-CT: enforce, max-age=30
Strict-Transport-Security: max-age=31536000; includeSubDomains
X-XSS-Protection: 1; mode=block
Referrer-Policy: no-referrer-when-downgrade
```

In response to multiple failed authentication attempts using invalid credentials within a brief timeframe, our API implements a security measure for user protection. This system will temporarily block all login attempts for the affected user account, even those made with valid credentials. During this period, any authentication request for this user will receive a `429 Too Many Requests` response. This measure is designed to enhance security by mitigating brute-force attacks and ensuring the safety of user accounts:

```bash
curl -I http://ignite.test/api/v1 \
-H 'Content-Type: application/json' \
-H 'Accept: application/json'

HTTP/1.1 429 Too Many Requests
Server: nginx/1.25.3
Content-Type: application/json
Connection: keep-alive
X-Xdebug-Profile-Filename: /var/tmp/cachegrind.out.408.gz
X-RateLimit-Limit: 1
X-RateLimit-Remaining: 0
Retry-After: 14
X-RateLimit-Reset: 1706729593
Cache-Control: no-cache, private
Date: Wed, 31 Jan 2024 19:32:59 GMT
Access-Control-Allow-Origin: *
Permissions-Policy: autoplay=(self), camera=(), encrypted-media=(self), fullscreen=(), geolocation=(self), gyroscope=(self), magnetometer=(), microphone=(), midi=(), payment=(), sync-xhr=(self), usb=()
X-Content-Type-Options: nosniff
Expect-CT: enforce, max-age=30
Strict-Transport-Security: max-age=31536000; includeSubDomains
X-XSS-Protection: 1; mode=block
Referrer-Policy: no-referrer-when-downgrade
```

## Parameters

Many API methods take optional parameters. For `GET` requests, any parameters not specified as a segment in the path can be passed as an `HTTP` query string parameter:

```bash
curl -I http://ignite.test/api/v1/users?organisation=ignite \
-H 'Content-Type: application/json' \
-H 'Accept: application/json'
```

For `POST`, `PATCH`, `PUT`, and `DELETE` requests, parameters not included in the URL should be encoded as JSON with a `Content-Type` of `application/json`:

```bash
curl -I -d '{"email":"default.user@ignite.com","password":"password"}' http://ignite.test/api/v1/login \
-H 'Content-Type: application/json' \
-H 'Accept: application/json'
```

## Root Endpoint

You can issue a `GET` request to the root endpoint to check the status of the REST API:

```bash
curl http://ignite.test/api/v1
```

## Client Errors

There are three possible types of client errors on API calls that receive request bodies:

1. Sending invalid JSON will result in a 400 Bad Request response.

```bash
HTTP/1.1 400 Bad Request
Content-Length: 124

{ "message": "Invalid JSON data." }
```

2. Sending invalid fields will result in a `422 Unprocessable Entity` response.

```bash
HTTP/1.1 422 Unprocessable Entity
Content-Length: 124

{
    "message": "The given data was invalid.",
    "errors": {
        "email": [
            "These credentials do not match our records."
        ]
    }
}
```

3. Sending requests to endpoints without appropriate permissions will results in a `403 Forbidden` response.

```bash
HTTP/1.1 403 Forbidden
Content-Length: 49

{ "message": "This action is unauthorized." }
```
## Pagination

Requests that return multiple items will be paginated to 15 items by default. You can specify further pages with the `page` parameter. For some resources, you can also set a custom page size up to 100 with the `per_page` parameter. Note that for technical reasons not all endpoints respect the `per_page` parameter.

```bash
curl 'http://ignite.test/api/v1/users?page=2&per_page=100'
```

## Rate Limiting

Our API enforces rate limiting to ensure fair usage and system stability. The rate limits are applied as follows:

1. **Authenticated Requests**: When using API token authentication, you can make up to 60 requests per minute. This limit is associated with the authenticated user, not the individual token. Consequently, if multiple applications or services are authorized by the same user, and they authenticate using different tokens belonging to that user, they collectively share the quota of 60 requests per minute.

2. **Unauthenticated Requests**: For requests that do not include API token authentication, the rate limit is set to 60 requests per minute. These unauthenticated requests are tracked based on the user agent, rather than being linked to a specific user account.

```bash
curl -i http://ignite.test/api/v1 \
-H 'Content-Type: application/json' \
-H 'Accept: application/json'

HTTP/1.1 200
x-ratelimit-limit: 60
x-ratelimit-remaining: 59
```
