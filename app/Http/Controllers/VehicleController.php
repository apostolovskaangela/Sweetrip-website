<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVehicleRequest;
use App\Http\Requests\UpdateVehicleRequest;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    /**
     * GET /api/vehicles
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->hasRole('manager')) {
            $vehicles = Vehicle::where('manager_id', $user->id)->get();
        } elseif ($user->hasRole('driver')) {
            $vehicleIds = $user->trips()->pluck('vehicle_id')->unique();
            $vehicles = Vehicle::whereIn('id', $vehicleIds)->get();
        } else {
            $vehicles = Vehicle::all();
        }

        return response()->json([
            'data' => $vehicles
        ]);
    }

    /**
     * GET /api/vehicles/{vehicle}
     */
    public function show(Vehicle $vehicle)
    {
        $vehicle->load(['trips.driver']);
        return response()->json([
            'vehicle' => $vehicle
        ]);
    }

    /**
     * POST /api/vehicles
     */
    public function store(StoreVehicleRequest $request)
    {
        $data = $request->validated();

        // If manager creating, set manager_id
        if (auth()->user()->isManager()) {
            $data['manager_id'] = auth()->id();
        }

        $vehicle = Vehicle::create($data);

        return response()->json([
            'message' => 'Vehicle created successfully',
            'vehicle' => $vehicle
        ]);
    }

    /**
     * PUT /api/vehicles/{vehicle}
     */
    public function update(UpdateVehicleRequest $request, Vehicle $vehicle)
    {
        $vehicle->update($request->validated());

        return response()->json([
            'message' => 'Vehicle updated successfully',
            'vehicle' => $vehicle
        ]);
    }

    /**
     * DELETE /api/vehicles/{vehicle}
     */
    public function destroy(Vehicle $vehicle)
    {
        if ($vehicle->trips()->exists()) {
            return response()->json([
                'message' => 'Cannot delete vehicle with existing trips'
            ], 400);
        }

        $vehicle->delete();

        return response()->json([
            'message' => 'Vehicle deleted successfully'
        ]);
    }
}
