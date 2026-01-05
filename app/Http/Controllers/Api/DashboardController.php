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

        if ($user->isDriver()) {
            $drivers = collect([$user]);
            $trips = $user->assignedTrips()->with('vehicle')->get();
            $vehicles = $user->assignedTrips()->with('vehicle')->get()->pluck('vehicle')->unique('id');
        } elseif ($user->isManager()) {
            $drivers = $user->drivers()->get();
            $vehicles = $user->managedVehicles()->get();
            $trips = Trip::whereHas('vehicle', function ($q) use ($user) {
                $q->where('manager_id', $user->id);
            })->with('driver', 'vehicle')->get();
        } else {
            // CEO/Admin: see everything
            $drivers = User::role('driver')->get();
            $trips = Trip::with('driver', 'vehicle')->get();
            $vehicles = Vehicle::all();
        }

        $activeTrips = $trips->where('status', '!=', TripStatus::COMPLETED)->count();

        $distanceToday = $trips->filter(function ($trip) {
            return $trip->trip_date == Carbon::today()->toDateString();
        })->sum('mileage');

        $totalTripsLastMonth = $trips->filter(function ($trip) {
            return $trip->trip_date >= Carbon::now()->subDays(30)->toDateString();
        })->count();

        $completedTripsLastMonth = $trips->filter(function ($trip) {
            return $trip->trip_date >= Carbon::now()->subDays(30)->toDateString()
                && $trip->status === TripStatus::COMPLETED;
        })->count();

        $efficiency = $totalTripsLastMonth > 0
            ? round(($completedTripsLastMonth / $totalTripsLastMonth) * 100, 1)
            : 0;

        $totalVehicles = $vehicles->count();

        return response()->json([
            'stats' => [
                'active_trips' => $activeTrips,
                'total_vehicles' => $totalVehicles,
                'distance_today' => $distanceToday,
                'efficiency' => $efficiency,
                'total_trips_last_month' => $totalTripsLastMonth,
                'completed_trips_last_month' => $completedTripsLastMonth,
            ],
            'drivers' => $drivers->map(function ($driver) {
                return [
                    'id' => $driver->id,
                    'name' => $driver->name,
                    'email' => $driver->email,
                ];
            }),
            'trips' => $trips->map(function ($trip) {
                return [
                    'id' => $trip->id,
                    'trip_number' => $trip->trip_number,
                    'status' => $trip->status->value,
                    'status_label' => $trip->status_label,
                    'trip_date' => $trip->trip_date,
                    'destination_from' => $trip->destination_from,
                    'destination_to' => $trip->destination_to,
                    'mileage' => $trip->mileage,
                    'driver' => $trip->driver ? [
                        'id' => $trip->driver->id,
                        'name' => $trip->driver->name,
                        'email' => $trip->driver->email,
                    ] : null,
                    'vehicle' => $trip->vehicle ? [
                        'id' => $trip->vehicle->id,
                        'registration_number' => $trip->vehicle->registration_number,
                    ] : null,
                ];
            }),
            'vehicles' => $vehicles->map(function ($vehicle) {
                return [
                    'id' => $vehicle->id,
                    'registration_number' => $vehicle->registration_number,
                    'is_active' => $vehicle->is_active,
                ];
            }),
        ]);
    }
}

