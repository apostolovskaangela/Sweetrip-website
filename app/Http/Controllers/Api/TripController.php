<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTripRequest;
use App\Http\Requests\UpdateTripRequest;
use App\Models\Trip;
use App\Models\User;
use App\Models\Vehicle;
use App\Notifications\TripAssigned;
use App\TripStatus;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TripController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
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

        return response()->json([
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
                    'a_code' => $trip->a_code,
                    'driver_description' => $trip->driver_description,
                    'admin_description' => $trip->admin_description,
                    'invoice_number' => $trip->invoice_number,
                    'amount' => $trip->amount,
                    'cmr' => $trip->cmr,
                    'cmr_url' => $trip->cmr_url,
                    'driver' => $trip->driver ? [
                        'id' => $trip->driver->id,
                        'name' => $trip->driver->name,
                        'email' => $trip->driver->email,
                    ] : null,
                    'vehicle' => $trip->vehicle ? [
                        'id' => $trip->vehicle->id,
                        'registration_number' => $trip->vehicle->registration_number,
                    ] : null,
                    'stops' => $trip->stops->map(function ($stop) {
                        return [
                            'id' => $stop->id,
                            'destination' => $stop->destination,
                            'stop_order' => $stop->stop_order,
                            'notes' => $stop->notes,
                        ];
                    }),
                ];
            }),
            'pagination' => [
                'current_page' => $trips->currentPage(),
                'last_page' => $trips->lastPage(),
                'per_page' => $trips->perPage(),
                'total' => $trips->total(),
            ],
        ]);
    }

    /**
     * Get available drivers and vehicles for trip creation.
     */
    // public function create(Request $request): JsonResponse
    // {
    //     $user = $request->user();

    //     // Drivers
    //     if ($user->isManager()) {
    //         $drivers = User::where('manager_id', $user->id)->get();
    //         $vehicles = Vehicle::where('manager_id', $user->id)
    //             ->where('is_active', true)
    //             ->get();
    //     } else {
    //         // Admin can see all
    //         $drivers = User::role('driver')->get();
    //         $vehicles = Vehicle::where('is_active', true)->get();
    //     }

    //     return response()->json([
    //         'drivers' => $drivers->map(function ($driver) {
    //             return [
    //                 'id' => $driver->id,
    //                 'name' => $driver->name,
    //                 'email' => $driver->email,
    //             ];
    //         }),
    //         'vehicles' => $vehicles->map(function ($vehicle) {
    //             return [
    //                 'id' => $vehicle->id,
    //                 'registration_number' => $vehicle->registration_number,
    //                 'make' => $vehicle->make ?? null,
    //                 'model' => $vehicle->model ?? null,
    //                 'is_active' => $vehicle->is_active,
    //             ];
    //         }),
    //     ]);
    // }
    public function create(Request $request): JsonResponse
{
    $user = $request->user();

    // MANAGER
    if ($user->role_id === 2) {
        $drivers = User::where('manager_id', $user->id)
            ->where('role_id', 4)
            ->get();

        $vehicles = Vehicle::where('manager_id', $user->id)
            ->where('is_active', true)
            ->get();
    }
    // CEO / ADMIN
    else {
        $drivers = User::where('role_id', 4)->get();
        $vehicles = Vehicle::where('is_active', true)->get();
    }

    return response()->json([
        'drivers' => $drivers->map(fn ($driver) => [
            'id' => $driver->id,
            'name' => $driver->name,
            'email' => $driver->email,
        ]),
        'vehicles' => $vehicles->map(fn ($vehicle) => [
            'id' => $vehicle->id,
            'registration_number' => $vehicle->registration_number,
            'make' => $vehicle->make ?? null,
            'model' => $vehicle->model ?? null,
            'is_active' => $vehicle->is_active,
        ]),
    ]);
}


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTripRequest $request): JsonResponse
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

        $trip->load(['driver', 'vehicle', 'stops', 'creator']);

        return response()->json([
            'message' => 'Trip created successfully. Driver has been notified.',
            'trip' => [
                'id' => $trip->id,
                'trip_number' => $trip->trip_number,
                'status' => $trip->status->value,
                'status_label' => $trip->status_label,
                'trip_date' => $trip->trip_date,
                'destination_from' => $trip->destination_from,
                'destination_to' => $trip->destination_to,
                'mileage' => $trip->mileage,
                'a_code' => $trip->a_code,
                'driver_description' => $trip->driver_description,
                'admin_description' => $trip->admin_description,
                'invoice_number' => $trip->invoice_number,
                'amount' => $trip->amount,
                'driver' => $trip->driver ? [
                    'id' => $trip->driver->id,
                    'name' => $trip->driver->name,
                    'email' => $trip->driver->email,
                ] : null,
                'vehicle' => $trip->vehicle ? [
                    'id' => $trip->vehicle->id,
                    'registration_number' => $trip->vehicle->registration_number,
                ] : null,
                'stops' => $trip->stops->map(function ($stop) {
                    return [
                        'id' => $stop->id,
                        'destination' => $stop->destination,
                        'stop_order' => $stop->stop_order,
                        'notes' => $stop->notes,
                    ];
                }),
            ],
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Trip $trip): JsonResponse
    {
        $trip->load(['driver', 'vehicle', 'stops', 'creator']);

        return response()->json([
            'trip' => [
                'id' => $trip->id,
                'trip_number' => $trip->trip_number,
                'status' => $trip->status->value,
                'status_label' => $trip->status_label,
                'trip_date' => $trip->trip_date,
                'destination_from' => $trip->destination_from,
                'destination_to' => $trip->destination_to,
                'mileage' => $trip->mileage,
                'a_code' => $trip->a_code,
                'driver_description' => $trip->driver_description,
                'admin_description' => $trip->admin_description,
                'invoice_number' => $trip->invoice_number,
                'amount' => $trip->amount,
                'cmr' => $trip->cmr,
                'cmr_url' => $trip->cmr_url,
                'driver' => $trip->driver ? [
                    'id' => $trip->driver->id,
                    'name' => $trip->driver->name,
                    'email' => $trip->driver->email,
                ] : null,
                'vehicle' => $trip->vehicle ? [
                    'id' => $trip->vehicle->id,
                    'registration_number' => $trip->vehicle->registration_number,
                ] : null,
                'creator' => $trip->creator ? [
                    'id' => $trip->creator->id,
                    'name' => $trip->creator->name,
                    'email' => $trip->creator->email,
                ] : null,
                'stops' => $trip->stops->map(function ($stop) {
                    return [
                        'id' => $stop->id,
                        'destination' => $stop->destination,
                        'stop_order' => $stop->stop_order,
                        'notes' => $stop->notes,
                    ];
                }),
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTripRequest $request, Trip $trip): JsonResponse
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

        $trip->load(['driver', 'vehicle', 'stops', 'creator']);

        return response()->json([
            'message' => 'Trip updated successfully.',
            'trip' => [
                'id' => $trip->id,
                'trip_number' => $trip->trip_number,
                'status' => $trip->status->value,
                'status_label' => $trip->status_label,
                'trip_date' => $trip->trip_date,
                'destination_from' => $trip->destination_from,
                'destination_to' => $trip->destination_to,
                'mileage' => $trip->mileage,
                'a_code' => $trip->a_code,
                'driver_description' => $trip->driver_description,
                'admin_description' => $trip->admin_description,
                'invoice_number' => $trip->invoice_number,
                'amount' => $trip->amount,
                'driver' => $trip->driver ? [
                    'id' => $trip->driver->id,
                    'name' => $trip->driver->name,
                    'email' => $trip->driver->email,
                ] : null,
                'vehicle' => $trip->vehicle ? [
                    'id' => $trip->vehicle->id,
                    'registration_number' => $trip->vehicle->registration_number,
                ] : null,
                'stops' => $trip->stops->map(function ($stop) {
                    return [
                        'id' => $stop->id,
                        'destination' => $stop->destination,
                        'stop_order' => $stop->stop_order,
                        'notes' => $stop->notes,
                    ];
                }),
            ],
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Trip $trip): JsonResponse
    {
        $this->authorize('delete', $trip);

        $trip->delete();

        return response()->json([
            'message' => 'Trip deleted successfully.',
        ]);
    }

    /**
     * Upload CMR document for a trip.
     */
    public function uploadCmr(Request $request, Trip $trip): JsonResponse
    {
        $request->validate([
            'cmr' => 'required|file|mimes:jpeg,png,jpg,pdf|max:5120',
        ]);

        if ($trip->driver_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized action.'], 403);
        }

        $path = $request->file('cmr')->store('cmr_uploads', 'public');

        $trip->update([
            'cmr' => $path,
        ]);

        return response()->json([
            'message' => 'CMR uploaded successfully.',
            'trip' => [
                'id' => $trip->id,
                'cmr' => $trip->cmr,
                'cmr_url' => $trip->cmr_url,
            ],
        ]);
    }
}

