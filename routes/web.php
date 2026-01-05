<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\VehicleController;
use App\Http\Middleware\Role;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->middleware('guest');

// Route::get('/', function () {
//     if (auth()->check()) {
//         return redirect()->route('dashboard');
//     }
//     return redirect()->route('login');
// });

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();

        if ($user->isDriver()) {
            return redirect()->route('drivers.dashboard');
        } else {
            return app(DashboardController::class)->index();
        }
    })->name('dashboard');

    // Trip routes
    Route::resource('trips', TripController::class);
    Route::post('/trips/{trip}/cmr', [DriverController::class, 'uploadCmr'])->name('trips.cmr');


    // Vehicle routes (CEO, admin and Manager only)
    Route::middleware(Role::class)->group(function () {
        Route::resource('vehicles', VehicleController::class);
    });

    // Driver routes
    Route::prefix('driver')->name('drivers.')->group(function () {
        Route::get('/dashboard', [DriverController::class, 'dashboard'])->name('dashboard');
        Route::post('/trips/{trip}/status', [DriverController::class, 'updateTripStatus'])->name('trips.status');
    });

    Route::middleware(Role::class)->group(function () {
        Route::resource('users', UserManagementController::class)->except(['show']);
    });
});

require __DIR__ . '/auth.php';
