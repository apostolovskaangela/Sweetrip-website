<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\DriverController;
use App\Http\Controllers\Api\TripController;
use App\Http\Controllers\Api\UserManagementController;
use App\Http\Controllers\Api\VehicleController;
use App\Http\Middleware\Role;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware(['auth:sanctum'])->group(function () {
    // Authentication
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Driver routes
    Route::prefix('driver')->group(function () {
        Route::get('/dashboard', [DriverController::class, 'dashboard']);
        Route::post('/trips/{trip}/status', [DriverController::class, 'updateTripStatus']);
        Route::post('/trips/{trip}/cmr', [DriverController::class, 'uploadCmr']);
    });

    // Trip routes
    Route::get('/trips/create', [TripController::class, 'create']); // Get available drivers/vehicles
    Route::apiResource('trips', TripController::class);
    Route::post('/trips/{trip}/cmr', [TripController::class, 'uploadCmr']);

    // Vehicle routes (CEO, admin and Manager only)
    Route::middleware(Role::class)->group(function () {
        Route::apiResource('vehicles', VehicleController::class);
    });

    // User management routes (CEO, admin and Manager only)
    Route::middleware(Role::class)->group(function () {
        Route::apiResource('users', UserManagementController::class)->except(['show']);
    });
});

