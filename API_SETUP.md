# API Setup Instructions

This document provides instructions for setting up the API for use with your React Native frontend.

## Prerequisites

- PHP 8.2 or higher
- Composer
- Laravel 12

## Installation Steps

### 1. Install Laravel Sanctum

Run the following command to install Laravel Sanctum for API authentication:

```bash
composer require laravel/sanctum
```

### 2. Publish Sanctum Configuration

```bash
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

### 3. Run Migrations

Sanctum will create a `personal_access_tokens` table. Run migrations:

```bash
php artisan migrate
```

### 4. Update User Model

The User model has already been updated with the `HasApiTokens` trait. Verify that `app/Models/User.php` includes:

```php
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, HasApiTokens;
    // ...
}
```

### 5. Configure API Routes

The API routes are already configured in `routes/api.php` and registered in `bootstrap/app.php`. Verify that `bootstrap/app.php` includes:

```php
->withRouting(
    web: __DIR__ . '/../routes/web.php',
    api: __DIR__ . '/../routes/api.php',
    // ...
)
```

### 6. Configure CORS (if needed)

If your React Native app will be making requests from a different domain, you may need to configure CORS. Update `config/cors.php` or add CORS middleware as needed.

### 7. Test the API

You can test the API endpoints using tools like Postman, Insomnia, or curl:

**Example Login:**
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"driver@example.com","password":"password"}'
```

**Example Authenticated Request:**
```bash
curl -X GET http://localhost:8000/api/dashboard \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

## API Base URL

- **Local Development**: `http://localhost:8000/api`
- **Production**: `https://your-domain.com/api`

## Authentication Flow

1. User logs in via `POST /api/login` with email and password
2. Server returns a token and user information
3. Client stores the token (e.g., in AsyncStorage for React Native)
4. Client includes the token in the `Authorization` header for all subsequent requests:
   ```
   Authorization: Bearer {token}
   ```
5. User logs out via `POST /api/logout` (token is revoked)

## Token Management

- Tokens are stored in the `personal_access_tokens` table
- Tokens are automatically revoked when the user logs out
- You can implement token refresh logic if needed
- Tokens do not expire by default, but you can configure expiration in `config/sanctum.php`

## Security Notes

1. Always use HTTPS in production
2. Store tokens securely in your React Native app (use secure storage)
3. Implement token refresh if needed
4. Handle 401 (Unauthorized) responses by redirecting to login
5. Validate all inputs on both client and server side

## Troubleshooting

### Issue: "Route [api.login] not defined"
- Make sure `routes/api.php` exists and is registered in `bootstrap/app.php`

### Issue: "Class 'Laravel\Sanctum\HasApiTokens' not found"
- Run `composer require laravel/sanctum` and verify the User model has the correct use statement

### Issue: "Table 'personal_access_tokens' doesn't exist"
- Run `php artisan migrate` to create the Sanctum tables

### Issue: 401 Unauthorized errors
- Verify the token is being sent in the Authorization header
- Check that the token hasn't been revoked
- Ensure the user is authenticated

## Next Steps

1. Install and configure Sanctum (steps above)
2. Test the login endpoint
3. Implement authentication in your React Native app
4. Test all API endpoints with your frontend
5. Deploy to production with proper security measures



