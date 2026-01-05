<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DriverController extends Controller
{
    /**
     * Display driver dashboard with assigned trips.
     */
    public function dashboard(Request $request): View
    {
        $driver = $request->user();

        if (!$driver->isDriver()) {
            abort(403);
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

        return view('drivers.dashboard', compact('trips', 'stats'));
    }

    /**
     * Update trip status (for drivers).
     */
    public function updateTripStatus(Request $request, Trip $trip)
    {
        $driver = $request->user();

        if (!$driver->isDriver() || $trip->driver_id !== $driver->id) {
            abort(403);
        }

        $request->validate([
            'status' => ['required', 'in:not_started,in_process,started,completed'],
        ]);

        if ($request->status === 'completed' && empty($trip->cmr)) {
            return back()->withErrors(['cmr' => 'You must upload the CMR before marking the trip as completed.']);
        }

        $trip->update([
            'status' => \App\TripStatus::from($request->status),
        ]);

        return redirect()->back()
            ->with('success', 'Trip status updated successfully.');
    }

    public function uploadCmr(Request $request, Trip $trip): RedirectResponse
    {
        $request->validate([
            'cmr' => 'required|file|mimes:jpeg,png,jpg,pdf|max:5120',
        ]);
        if ($trip->driver_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $path = $request->file('cmr')->store('cmr_uploads', 'public');

        $trip->update(['cmr' => $path]);

        return back()->with('success', 'CMR uploaded successfully.');
    }
}
