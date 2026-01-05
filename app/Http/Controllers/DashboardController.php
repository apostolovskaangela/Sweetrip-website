<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\User;
use App\Models\Vehicle;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

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

        $activeTrips = $trips->where('status', '!=', 'completed')->count();

        $distanceToday = $trips->filter(function ($trip) {
            return $trip->trip_date == Carbon::today()->toDateString();
        })->sum('mileage');

        $totalTripsLastMonth = $trips->filter(function ($trip) {
            return $trip->trip_date >= Carbon::now()->subDays(30)->toDateString();
        })->count();

        $completedTripsLastMonth = $trips->filter(function ($trip) {
            return $trip->trip_date >= Carbon::now()->subDays(30)->toDateString()
                && $trip->status === 'completed';
        })->count();

        $efficiency = $totalTripsLastMonth > 0
            ? round(($completedTripsLastMonth / $totalTripsLastMonth) * 100, 1)
            : 0;

        $totalVehicles = $vehicles->count();

        return view('managers.dashboard', compact(
            'activeTrips',
            'totalVehicles',
            'distanceToday',
            'efficiency',
            'drivers',
            'trips',
            'vehicles',
        ));
    }
}
