<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTripRequest;
use App\Http\Requests\UpdateTripRequest;
use App\Models\Trip;
use App\Models\User;
use App\Models\Vehicle;
use App\Notifications\TripAssigned;
use App\TripStatus;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class TripController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $query = Trip::with(['driver', 'vehicle', 'stops']);

        if ($user->hasRole('driver')) {
            // Drivers only see their own trips
            $query->where('driver_id', $user->id);
        } elseif ($user->hasRole('manager')) {
            // Managers see trips of their drivers only
            $driverIds = User::where('manager_id', $user->id)->pluck('id');
            $query->whereIn('driver_id', $driverIds);
        }
        // Admin sees all trips, no filter needed

        $trips = $query->latest('trip_date')->paginate(15);

        return view('trips.index', compact('trips'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();

        // Drivers
        if ($user->isManager()) {
            $drivers = User::where('manager_id', $user->id)->get();
            $vehicles = Vehicle::where('manager_id', $user->id)
                ->where('is_active', true)
                ->get();
        } else {
            // Admin can see all
            $drivers = User::role('driver')->get();
            $vehicles = Vehicle::where('is_active', true)->get();
        }

        return view('trips.create', compact('drivers', 'vehicles'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTripRequest $request): RedirectResponse
    {
        $trip = DB::transaction(function () use ($request) {
            $trip = Trip::create([
                ...$request->validated(),
                'created_by' => $request->user()->id,
                'status' => $request->validated()['status'] ?? TripStatus::NOT_STARTED,
            ]);

            // Create stops if provided
            if ($request->has('stops')) {
                foreach ($request->stops as $stop) {
                    $trip->stops()->create($stop);
                }
            }

            return $trip;
        });

        // Notify driver when assigned
        $driver = $trip->driver;
        $driver->notify(new TripAssigned($trip));

        return redirect()->route('trips.show', $trip)
            ->with('success', 'Trip created successfully. Driver has been notified.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Trip $trip): View
    {
        // $this->authorize('view', $trip);

        $trip->load(['driver', 'vehicle', 'stops', 'creator']);

        return view('trips.show', compact('trip'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Trip $trip): View
    {
        // $this->authorize('update', $trip);

        $vehicles = Vehicle::where('is_active', true)->get();
        $drivers = User::role('driver')->get();
        $trip->load('stops');

        return view('trips.edit', compact('trip', 'vehicles', 'drivers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTripRequest $request, Trip $trip): RedirectResponse
    {
        $oldDriverId = $trip->driver_id;

        DB::transaction(function () use ($request, $trip) {
            $trip->update($request->validated());

            // Update stops if provided
            if ($request->has('stops')) {
                $trip->stops()->delete();
                foreach ($request->stops as $stop) {
                    $trip->stops()->create($stop);
                }
            }
        });

        // Notify driver if driver changed
        if ($trip->wasChanged('driver_id') && $trip->driver_id !== $oldDriverId) {
            $trip->driver->notify(new TripAssigned($trip));
        }

        return redirect()->route('trips.show', $trip)
            ->with('success', 'Trip updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Trip $trip): RedirectResponse
    {
        $this->authorize('delete', $trip);

        $trip->delete();

        return redirect()->route('trips.index')
            ->with('success', 'Trip deleted successfully.');
    }

    public function uploadCmr(Request $request, Trip $trip): RedirectResponse
    {
        $request->validate([
            'cmr' => 'required|image|mimes:jpeg,png,jpg,pdf|max:5120',
        ]);

        if ($trip->driver_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $path = $request->file('cmr')->store('cmr_uploads', 'public');

        $trip->update([
            'cmr' => $path,
        ]);

        return back()->with('success', 'CMR uploaded successfully.');
    }
}
