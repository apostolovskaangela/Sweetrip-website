# Road Management System for Truck Drivers - Sweden

A comprehensive Laravel 12 web application for managing transportation orders for truck drivers in Sweden. The system follows SOLID and DRY principles to ensure scalability and maintainability.

## Features

### User Roles
- **CEO** - Full administrative access to all users, orders, and reports
- **Manager** - Can create, edit, and assign orders/trips to their drivers
- **Admin** - Can create, edit, and assign orders/trips to all drivers
- **Driver** - Can view assigned trips and update trip status

### Trip/Order Management
- Unique trip numbers
- Vehicle assignment
- Driver assignment with notifications
- A-Code for logistics tracking
- Multiple destination stops support
- Status tracking (Not Started, In Process, Started, Completed)
- Mileage tracking
- Driver and Admin descriptions
- Invoice and amount management

### Notifications
- In-app and email notifications when a driver is assigned to a trip
- Real-time status updates

## Technology Stack

- **Laravel 12** - Latest PHP framework with native typed properties
- **Spatie Laravel Permissions** - Role-based access control
- **Tailwind CSS** - Modern UI framework
- **Blade Components** - Dynamic UI rendering
- **Laravel Notifications** - Email and database notifications

## Installation

1. Clone the repository
2. Install dependencies:
   ```bash
   composer install
   ```
3. Set up environment:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
4. Configure your database in `.env`
5. Run migrations and seeders:
   ```bash
   php artisan migrate --seed
   ```
6. Start the development server:
   ```bash
   php artisan serve
   ```

## Default User Accounts

After running the seeder, you can log in with:

- **CEO**: ceo@example.com / password
- **Manager**: manager@example.com / password
- **Driver**: driver@example.com / password

## Database Structure

### Tables
- `users` - User accounts with role-based access
- `vehicles` - Vehicle information
- `trips` - Trip/order details
- `trip_stops` - Multiple stops for trips
- `roles` & `permissions` - Spatie permissions tables
- `notifications` - Laravel notifications table

## Architecture Principles

### SOLID Principles
- **Single Responsibility**: Each class has a single, well-defined purpose
- **Open/Closed**: Classes are open for extension but closed for modification
- **Liskov Substitution**: Proper use of interfaces and inheritance
- **Interface Segregation**: Focused interfaces for specific needs
- **Dependency Inversion**: Dependencies injected through constructors

### DRY (Don't Repeat Yourself)
- Reusable components and views
- Centralized validation logic in Form Requests
- Policy-based authorization
- Service layer for business logic

## Key Components

### Models
- `User` - User model with role relationships
- `Trip` - Trip model with vehicle, driver, and stops relationships
- `Vehicle` - Vehicle model
- `TripStop` - Additional stops for trips

### Controllers
- `TripController` - RESTful trip management
- `VehicleController` - Vehicle CRUD operations
- `DriverController` - Driver dashboard and status updates

### Policies
- `TripPolicy` - Authorization logic for trip access

### Form Requests
- `StoreTripRequest` - Trip creation validation
- `UpdateTripRequest` - Trip update validation
- `StoreVehicleRequest` - Vehicle validation

### Notifications
- `TripAssigned` - Notifies drivers when assigned to trips

## Routes

### Authenticated Routes
- `/dashboard` - Role-based dashboard redirect
- `/trips` - Trip management (resource routes)
- `/vehicles` - Vehicle management (CEO/Manager only)
- `/driver/dashboard` - Driver dashboard

### Authentication
- `/login` - Login page
- `/logout` - Logout (POST)

## Security Features

- Role-based access control via Spatie Permissions
- Policy-based authorization for resources
- CSRF protection on all forms
- Password hashing
- Session management

## Development Guidelines

1. Follow PSR-12 coding standards
2. Use Form Requests for validation
3. Use Policies for authorization
4. Keep controllers thin - delegate to services when needed
5. Use Eloquent relationships efficiently
6. Follow RESTful conventions

## License

This project is proprietary software developed for truck driver management in Sweden.
