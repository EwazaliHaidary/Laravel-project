# Library Management API – Authentication Flow

## 1. Overview

The Library Management API uses Laravel Sanctum for API authentication.

The API base URL is:

`http://localhost:8000/api/v1`

## 2. Register

**Endpoint:** `POST /register`

**Authentication:** Not required.

**Request Body (JSON):**

```json
{
    "name": "Test User",
    "email": "test@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

**Success Response:** `201 Created`

Returns a success message, user information, and an API token.

## 3. Login

**Endpoint:** `POST /login`

**Authentication:** Not required.

**Request Body (JSON):**

```json
{
    "email": "test@example.com",
    "password": "password123"
}
```

**Success Response:** `200 OK`

Returns a success message, user information, and an API token.

## 4. Access Protected Routes

Protected endpoints require a valid Sanctum token.

Add the following HTTP header to requests:

`Authorization: Bearer YOUR_API_TOKEN`

Protected endpoints include:

- `GET /me`
- `POST /me/avatar`
- `POST /logout`
- Authors CRUD
- Books CRUD
- Categories CRUD
- Borrowings CRUD

## 5. Get Authenticated User

**Endpoint:** `GET /me`

**Authentication:** Required.

Returns the authenticated user's information, including their avatar URL.

## 6. Logout

**Endpoint:** `POST /logout`

**Authentication:** Required.

Deletes the current access token and returns:

`204 No Content`

## 7. Authentication Errors

| Status Code | Description |
|---|---|
| 401 | Unauthenticated or invalid credentials |
| 403 | User is not authorized |
| 422 | Validation failed |

## 8. Security Notes

- Never share API tokens publicly.
- Protected endpoints require a valid Bearer token.
- Passwords are hashed before storage.
- User passwords and remember tokens are not included in UserResource responses.
