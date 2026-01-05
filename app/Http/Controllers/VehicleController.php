<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVehicleRequest;
use App\Http\Requests\UpdateVehicleRequest;
use App\Models\Vehicle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VehicleController extends Controller
{
    public function __construct()
    {
        //
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        if ($user->hasRole('manager')) {
            // Managers only see vehicles assigned to them
            $vehicles = Vehicle::where('manager_id', $user->id)->get();
        } elseif ($user->hasRole('driver')) {
            // Drivers only see vehicles they are assigned to trips for
            $vehicleIds = Trip::where('driver_id', $user->id)->pluck('vehicle_id')->unique();
            $vehicles = Vehicle::whereIn('id', $vehicleIds)->get();
        } else {
            // Admin sees all
            $vehicles = Vehicle::all();
        }

        return view('vehicles.index', compact('vehicles'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $user = auth()->user();

        if ($user->isManager()) {
            $vehicles = Vehicle::where('manager_id', $user->id)->get();
        } else {
            $vehicles = Vehicle::all(); // admin can see all
        }

        return view('vehicles.create', compact('vehicles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // Validation rules
        $rules = [
            'registration_number' => 'required|string|max:255|unique:vehicles,registration_number',
            'notes' => 'nullable|string',
            'is_active' => 'required|boolean',
        ];

        // Admin must select a manager
        if (auth()->user()->isAdmin()) {
            $rules['manager_id'] = 'required|exists:users,id';
        }

        // Validate request
        $validated = $request->validate($rules);

        // Automatically assign manager_id if user is a manager
        if (auth()->user()->isManager()) {
            $validated['manager_id'] = auth()->id();
        }

        // Create the vehicle
        Vehicle::create($validated);

        return redirect()->route('vehicles.index')
            ->with('success', 'Vehicle created successfully.');
    }



    /**
     * Display the specified resource.
     */
    public function show(Vehicle $vehicle): View
    {
        $vehicle->load(['trips.driver']);

        return view('vehicles.show', compact('vehicle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vehicle $vehicle): View
    {
        return view('vehicles.edit', compact('vehicle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVehicleRequest $request, Vehicle $vehicle): RedirectResponse
    {
        $vehicle->update($request->validated());

        return redirect()->route('vehicles.show', $vehicle)
            ->with('success', 'Vehicle updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vehicle $vehicle): RedirectResponse
    {
        if ($vehicle->trips()->exists()) {
            return redirect()->route('vehicles.index')
                ->with('error', 'Cannot delete vehicle with existing trips.');
        }

        $vehicle->delete();

        return redirect()->route('vehicles.index')
            ->with('success', 'Vehicle deleted successfully.');
    }
}
