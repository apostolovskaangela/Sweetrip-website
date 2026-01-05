# API Documentation

This document describes the API endpoints available for the React Native frontend.

## Base URL

All API endpoints are prefixed with `/api`. For example:
- Production: `https://your-domain.com/api`
- Local: `http://localhost:8000/api`

## Authentication

The API uses Laravel Sanctum for token-based authentication.

### Login

**POST** `/api/login`

Request body:
```json
{
  "email": "driver@example.com",
  "password": "password"
}
```

Response:
```json
{
  "user": {
    "id": 1,
    "name": "Driver Name",
    "email": "driver@example.com",
    "roles": ["driver"]
  },
  "token": "1|xxxxxxxxxxxx"
}
```

### Get Authenticated User

**GET** `/api/user`

Headers:
```
Authorization: Bearer {token}
```

Response:
```json
{
  "id": 1,
  "name": "Driver Name",
  "email": "driver@example.com",
  "roles": ["driver"]
}
```

### Logout

**POST** `/api/logout`

Headers:
```
Authorization: Bearer {token}
```

Response:
```json
{
  "message": "Logged out successfully."
}
```

## Dashboard

### Get Dashboard Data

**GET** `/api/dashboard`

Headers:
```
Authorization: Bearer {token}
```

Response:
```json
{
  "stats": {
    "active_trips": 5,
    "total_vehicles": 10,
    "distance_today": 450.5,
    "efficiency": 85.5,
    "total_trips_last_month": 100,
    "completed_trips_last_month": 85
  },
  "drivers": [...],
  "trips": [...],
  "vehicles": [...]
}
```

## Driver Endpoints

### Get Driver Dashboard

**GET** `/api/driver/dashboard`

Headers:
```
Authorization: Bearer {token}
```

Response:
```json
{
  "stats": {
    "total_trips": 50,
    "completed_trips": 45,
    "pending_trips": 5
  },
  "trips": [...],
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 10,
    "total": 50
  }
}
```

### Update Trip Status

**POST** `/api/driver/trips/{trip}/status`

Headers:
```
Authorization: Bearer {token}
```

Request body:
```json
{
  "status": "in_process"
}
```

Valid status values: `not_started`, `in_process`, `started`, `completed`

Response:
```json
{
  "message": "Trip status updated successfully.",
  "trip": {
    "id": 1,
    "status": "in_process",
    "status_label": "In Process"
  }
}
```

### Upload CMR Document

**POST** `/api/driver/trips/{trip}/cmr`

Headers:
```
Authorization: Bearer {token}
Content-Type: multipart/form-data
```

Request body (form-data):
```
cmr: [file] (jpeg, png, jpg, pdf, max 5MB)
```

Response:
```json
{
  "message": "CMR uploaded successfully.",
  "trip": {
    "id": 1,
    "cmr": "cmr_uploads/xxxxx.pdf",
    "cmr_url": "http://localhost:8000/storage/cmr_uploads/xxxxx.pdf"
  }
}
```

## Trip Endpoints

### List Trips

**GET** `/api/trips`

Headers:
```
Authorization: Bearer {token}
```

Query parameters:
- `page` (optional): Page number for pagination

Response:
```json
{
  "trips": [
    {
      "id": 1,
      "trip_number": "TRIP-001",
      "status": "not_started",
      "status_label": "Not Started",
      "trip_date": "2024-01-15",
      "destination_from": "Stockholm",
      "destination_to": "Gothenburg",
      "mileage": 450.5,
      "a_code": "A123",
      "driver_description": "Driver notes",
      "admin_description": "Admin notes",
      "invoice_number": "INV-001",
      "amount": 5000.00,
      "cmr": null,
      "cmr_url": null,
      "driver": {
        "id": 1,
        "name": "Driver Name",
        "email": "driver@example.com"
      },
      "vehicle": {
        "id": 1,
        "registration_number": "ABC123"
      },
      "stops": [
        {
          "id": 1,
          "destination": "Uppsala",
          "stop_order": 1,
          "notes": "Stop notes"
        }
      ]
    }
  ],
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

### Get Single Trip

**GET** `/api/trips/{trip}`

Headers:
```
Authorization: Bearer {token}
```

Response:
```json
{
  "trip": {
    "id": 1,
    "trip_number": "TRIP-001",
    ...
  }
}
```

### Get Available Drivers and Vehicles

**GET** `/api/trips/create`

Headers:
```
Authorization: Bearer {token}
```

Response:
```json
{
  "drivers": [
    {
      "id": 1,
      "name": "Driver Name",
      "email": "driver@example.com"
    }
  ],
  "vehicles": [
    {
      "id": 1,
      "registration_number": "ABC123",
      "make": "Volvo",
      "model": "FH16",
      "is_active": true
    }
  ]
}
```

### Create Trip

**POST** `/api/trips`

Headers:
```
Authorization: Bearer {token}
```

Request body:
```json
{
  "trip_number": "TRIP-001",
  "vehicle_id": 1,
  "driver_id": 1,
  "a_code": "A123",
  "destination_from": "Stockholm",
  "destination_to": "Gothenburg",
  "status": "not_started",
  "mileage": 450.5,
  "driver_description": "Driver notes",
  "admin_description": "Admin notes",
  "trip_date": "2024-01-15",
  "invoice_number": "INV-001",
  "amount": 5000.00,
  "stops": [
    {
      "destination": "Uppsala",
      "stop_order": 1,
      "notes": "Stop notes"
    }
  ]
}
```

Response: 201 Created
```json
{
  "message": "Trip created successfully. Driver has been notified.",
  "trip": {...}
}
```

### Update Trip

**PUT/PATCH** `/api/trips/{trip}`

Headers:
```
Authorization: Bearer {token}
```

Request body: (same as create, but all fields are optional)

Response:
```json
{
  "message": "Trip updated successfully.",
  "trip": {...}
}
```

### Delete Trip

**DELETE** `/api/trips/{trip}`

Headers:
```
Authorization: Bearer {token}
```

Response:
```json
{
  "message": "Trip deleted successfully."
}
```

### Upload CMR (Alternative endpoint)

**POST** `/api/trips/{trip}/cmr`

Same as driver CMR upload endpoint.

## Vehicle Endpoints

### List Vehicles

**GET** `/api/vehicles`

Headers:
```
Authorization: Bearer {token}
```

Response:
```json
{
  "vehicles": [
    {
      "id": 1,
      "registration_number": "ABC123",
      "make": "Volvo",
      "model": "FH16",
      "year": 2020,
      "notes": "Vehicle notes",
      "is_active": true,
      "manager_id": 2,
      "manager": {
        "id": 2,
        "name": "Manager Name",
        "email": "manager@example.com"
      }
    }
  ]
}
```

### Get Single Vehicle

**GET** `/api/vehicles/{vehicle}`

Headers:
```
Authorization: Bearer {token}
```

Response:
```json
{
  "vehicle": {
    "id": 1,
    "registration_number": "ABC123",
    ...
    "trips": [...]
  }
}
```

### Create Vehicle

**POST** `/api/vehicles`

Headers:
```
Authorization: Bearer {token}
```

Request body:
```json
{
  "registration_number": "ABC123",
  "make": "Volvo",
  "model": "FH16",
  "year": 2020,
  "notes": "Vehicle notes",
  "is_active": true,
  "manager_id": 2
}
```

Note: `manager_id` is required for admins, automatically set for managers.

Response: 201 Created

### Update Vehicle

**PUT/PATCH** `/api/vehicles/{vehicle}`

Headers:
```
Authorization: Bearer {token}
```

Request body: (same as create, but all fields are optional)

### Delete Vehicle

**DELETE** `/api/vehicles/{vehicle}`

Headers:
```
Authorization: Bearer {token}
```

Response:
```json
{
  "message": "Vehicle deleted successfully."
}
```

Note: Cannot delete vehicles with existing trips.

## User Management Endpoints

**Note:** These endpoints are only accessible to CEO, Admin, and Manager roles.

### List Users

**GET** `/api/users`

Headers:
```
Authorization: Bearer {token}
```

Response:
```json
{
  "users": [
    {
      "id": 1,
      "name": "User Name",
      "email": "user@example.com",
      "roles": ["driver"],
      "manager_id": 2
    }
  ]
}
```

### Create User

**POST** `/api/users`

Headers:
```
Authorization: Bearer {token}
```

Request body:
```json
{
  "name": "User Name",
  "email": "user@example.com",
  "password": "password123",
  "role": "driver",
  "manager_id": 2
}
```

Response: 201 Created

### Update User

**PUT/PATCH** `/api/users/{user}`

Headers:
```
Authorization: Bearer {token}
```

Request body:
```json
{
  "name": "Updated Name",
  "email": "updated@example.com",
  "password": "newpassword123",
  "role": "driver",
  "manager_id": 2
}
```

Note: `password` is optional. If not provided, the existing password is kept.

### Delete User

**DELETE** `/api/users/{user}`

Headers:
```
Authorization: Bearer {token}
```

Response:
```json
{
  "message": "User deleted successfully"
}
```

## Error Responses

All error responses follow this format:

```json
{
  "message": "Error message",
  "errors": {
    "field": ["Error message for field"]
  }
}
```

Common HTTP status codes:
- `200` - Success
- `201` - Created
- `401` - Unauthorized (invalid or missing token)
- `403` - Forbidden (insufficient permissions)
- `422` - Validation Error
- `404` - Not Found
- `500` - Server Error

## Role-Based Access

- **Driver**: Can only view and update their own trips
- **Manager**: Can view trips and vehicles assigned to their drivers
- **Admin/CEO**: Can view and manage all trips, vehicles, and users

## Notes

1. All dates are in `YYYY-MM-DD` format
2. All monetary values are in decimal format (e.g., `5000.00`)
3. File uploads use `multipart/form-data` content type
4. The `cmr_url` field provides a full URL to access the CMR document
5. Pagination is available for list endpoints that return collections

