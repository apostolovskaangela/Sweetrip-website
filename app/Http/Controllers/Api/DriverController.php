<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Trip;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    /**
     * Display driver dashboard with assigned trips.
     */
    public function dashboard(Request $request): JsonResponse
    {
        $driver = $request->user();

        if (!$driver->isDriver()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $trips = Trip::where('driver_id', $driver->id)
            ->with(['vehicle', 'stops'])
            ->latest('trip_date')
            ->paginate(10);

        $stats = [
            'total_trips' => Trip::where('driver_id', $driver->id)->count(),
            'completed_trips' => Trip::where('driver_id', $driver->id)
                ->where('status', \App\TripStatus::COMPLETED)
                ->count(),
            'pending_trips' => Trip::where('driver_id', $driver->id)
                ->whereIn('status', [
                    \App\TripStatus::NOT_STARTED,
                    \App\TripStatus::IN_PROCESS,
                    \App\TripStatus::STARTED,
                ])
                ->count(),
        ];

        return response()->json([
            'stats' => $stats,
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
     * Update trip status (for drivers).
     */
    public function updateTripStatus(Request $request, Trip $trip): JsonResponse
    {
        $driver = $request->user();

        if (!$driver->isDriver() || $trip->driver_id !== $driver->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'status' => ['required', 'in:not_started,in_process,started,completed'],
        ]);

        if ($request->status === 'completed' && empty($trip->cmr)) {
            return response()->json([
                'message' => 'You must upload the CMR before marking the trip as completed.',
                'errors' => ['cmr' => ['You must upload the CMR before marking the trip as completed.']],
            ], 422);
        }

        $trip->update([
            'status' => \App\TripStatus::from($request->status),
        ]);

        return response()->json([
            'message' => 'Trip status updated successfully.',
            'trip' => [
                'id' => $trip->id,
                'status' => $trip->status->value,
                'status_label' => $trip->status_label,
            ],
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

        $trip->update(['cmr' => $path]);

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



