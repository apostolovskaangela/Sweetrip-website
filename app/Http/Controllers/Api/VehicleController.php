<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVehicleRequest;
use App\Http\Requests\UpdateVehicleRequest;
use App\Models\Trip;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
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

        return response()->json([
            'vehicles' => $vehicles->map(function ($vehicle) {
                return [
                    'id' => $vehicle->id,
                    'registration_number' => $vehicle->registration_number,
                    'make' => $vehicle->make ?? null,
                    'model' => $vehicle->model ?? null,
                    'year' => $vehicle->year ?? null,
                    'notes' => $vehicle->notes,
                    'is_active' => $vehicle->is_active,
                    'manager_id' => $vehicle->manager_id,
                    'manager' => $vehicle->manager ? [
                        'id' => $vehicle->manager->id,
                        'name' => $vehicle->manager->name,
                        'email' => $vehicle->manager->email,
                    ] : null,
                ];
            }),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        // Validation rules
        $rules = [
            'registration_number' => 'required|string|max:255|unique:vehicles,registration_number',
            'make' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'notes' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ];

        // Admin must select a manager
        if ($request->user()->isAdmin()) {
            $rules['manager_id'] = 'required|exists:users,id';
        }

        // Validate request
        $validated = $request->validate($rules);

        // Automatically assign manager_id if user is a manager
        if ($request->user()->isManager()) {
            $validated['manager_id'] = $request->user()->id;
        }

        // Create the vehicle
        $vehicle = Vehicle::create($validated);
        $vehicle->load('manager');

        return response()->json([
            'message' => 'Vehicle created successfully.',
            'vehicle' => [
                'id' => $vehicle->id,
                'registration_number' => $vehicle->registration_number,
                'make' => $vehicle->make ?? null,
                'model' => $vehicle->model ?? null,
                'year' => $vehicle->year ?? null,
                'notes' => $vehicle->notes,
                'is_active' => $vehicle->is_active,
                'manager_id' => $vehicle->manager_id,
                'manager' => $vehicle->manager ? [
                    'id' => $vehicle->manager->id,
                    'name' => $vehicle->manager->name,
                    'email' => $vehicle->manager->email,
                ] : null,
            ],
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Vehicle $vehicle): JsonResponse
    {
        $vehicle->load(['trips.driver']);

        return response()->json([
            'vehicle' => [
                'id' => $vehicle->id,
                'registration_number' => $vehicle->registration_number,
                'make' => $vehicle->make ?? null,
                'model' => $vehicle->model ?? null,
                'year' => $vehicle->year ?? null,
                'notes' => $vehicle->notes,
                'is_active' => $vehicle->is_active,
                'manager_id' => $vehicle->manager_id,
                'manager' => $vehicle->manager ? [
                    'id' => $vehicle->manager->id,
                    'name' => $vehicle->manager->name,
                    'email' => $vehicle->manager->email,
                ] : null,
                'trips' => $vehicle->trips->map(function ($trip) {
                    return [
                        'id' => $trip->id,
                        'trip_number' => $trip->trip_number,
                        'status' => $trip->status->value,
                        'status_label' => $trip->status_label,
                        'trip_date' => $trip->trip_date,
                        'driver' => $trip->driver ? [
                            'id' => $trip->driver->id,
                            'name' => $trip->driver->name,
                            'email' => $trip->driver->email,
                        ] : null,
                    ];
                }),
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVehicleRequest $request, Vehicle $vehicle): JsonResponse
    {
        $vehicle->update($request->validated());
        $vehicle->load('manager');

        return response()->json([
            'message' => 'Vehicle updated successfully.',
            'vehicle' => [
                'id' => $vehicle->id,
                'registration_number' => $vehicle->registration_number,
                'make' => $vehicle->make ?? null,
                'model' => $vehicle->model ?? null,
                'year' => $vehicle->year ?? null,
                'notes' => $vehicle->notes,
                'is_active' => $vehicle->is_active,
                'manager_id' => $vehicle->manager_id,
                'manager' => $vehicle->manager ? [
                    'id' => $vehicle->manager->id,
                    'name' => $vehicle->manager->name,
                    'email' => $vehicle->manager->email,
                ] : null,
            ],
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vehicle $vehicle): JsonResponse
    {
        if ($vehicle->trips()->exists()) {
            return response()->json([
                'message' => 'Cannot delete vehicle with existing trips.',
            ], 422);
        }

        $vehicle->delete();

        return response()->json([
            'message' => 'Vehicle deleted successfully.',
        ]);
    }
}



