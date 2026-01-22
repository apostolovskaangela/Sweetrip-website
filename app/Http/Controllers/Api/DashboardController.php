<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Trip;
use App\Models\User;
use App\Models\Vehicle;
use App\TripStatus;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        // -----------------------------
        // Load data based on role
        // -----------------------------
        if ($user->isDriver()) {
            $drivers = collect([$user]);

            $trips = $user->assignedTrips()
                ->with('driver', 'vehicle')
                ->get();

            $vehicles = $trips->pluck('vehicle')->filter()->unique('id')->values();
        } elseif ($user->isManager()) {
            $drivers = $user->drivers()->get();

            $vehicles = $user->managedVehicles()->get();

            $trips = Trip::whereHas('vehicle', function ($q) use ($user) {
                $q->where('manager_id', $user->id);
            })
                ->with('driver', 'vehicle')
                ->get();
        } else {
            // CEO / Admin
            $driverRoleId = 4;
            $drivers = User::where('role_id', $driverRoleId)->get();
            $trips = Trip::with('driver', 'vehicle')->get();
            $vehicles = Vehicle::all();
        }

        // -----------------------------
        // Statistics
        // -----------------------------

        // Active trips = not completed
        $activeTrips = $trips->filter(fn($trip) => $trip->status !== TripStatus::COMPLETED)->count();

        // Distance today = only COMPLETED trips today
        $distanceToday = $trips->filter(function ($trip) {
            return $trip->trip_date === Carbon::today()->toDateString()
                && $trip->status === TripStatus::COMPLETED;
        })->sum('mileage');

        // Last 30 days
        $fromDate = Carbon::now()->subDays(30)->toDateString();

        $totalTripsLastMonth = $trips->filter(function ($trip) use ($fromDate) {
            return $trip->trip_date >= $fromDate;
        })->count();

        $completedTripsLastMonth = $trips->filter(function ($trip) use ($fromDate) {
            return $trip->trip_date >= $fromDate
                && $trip->status === TripStatus::COMPLETED;
        })->count();

        $efficiency = $totalTripsLastMonth > 0
            ? round(($completedTripsLastMonth / $totalTripsLastMonth) * 100, 1)
            : 0;

        $totalVehicles = $vehicles->count();

        // Recent trips for dashboard list
        $recentTrips = $trips->sortByDesc('trip_date')->take(5)->values();

        // -----------------------------
        // Response
        // -----------------------------
        return response()->json([
            'stats' => [
                'active_trips' => $activeTrips,
                'total_vehicles' => $totalVehicles,
                'distance_today' => $distanceToday,
                'efficiency' => $efficiency,
                'total_trips_last_month' => $totalTripsLastMonth,
                'completed_trips_last_month' => $completedTripsLastMonth,
            ],

            'drivers' => $drivers->map(fn($driver) => [
                'id' => $driver->id,
                'name' => $driver->name,
                'email' => $driver->email,
            ]),

            'recent_trips' => $recentTrips->map(fn($trip) => [
                'id' => $trip->id,
                'trip_number' => $trip->trip_number,
                'trip_date' => $trip->trip_date,
                'status' => $trip->status->value,
                'status_label' => $trip->status_label,
                'destination_from' => $trip->destination_from,
                'destination_to' => $trip->destination_to,
                'mileage' => $trip->mileage,
                'driver' => $trip->driver ? [
                    'id' => $trip->driver->id,
                    'name' => $trip->driver->name,
                ] : null,
                'vehicle' => $trip->vehicle ? [
                    'id' => $trip->vehicle->id,
                    'registration_number' => $trip->vehicle->registration_number,
                ] : null,
            ]),

            'vehicles' => $vehicles->map(fn($vehicle) => [
                'id' => $vehicle->id,
                'registration_number' => $vehicle->registration_number,
                'is_active' => $vehicle->is_active,
            ]),
        ]);
    }
}
