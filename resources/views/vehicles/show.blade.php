@extends('layouts.auth')

@section('title', 'Vehicle Details')

@section('content')
    <div class="mb-6 flex flex-col md:flex-row justify-between md:items-center gap-4">
        <h1 class="text-xl md:text-3xl font-bold tracking-tight">Vehicle: {{ $vehicle->registration_number }}</h1>
        <div class="space-x-2">
            @if (auth()->user()->isManager() || auth()->user()->isAdmin())
                <a href="{{ route('vehicles.edit', $vehicle) }}"
                    class="bg-[hsl(217,91%,35%)] text-white font-sm md:font-base font-bold py-1 md:py-2 px-2 md:px-4 rounded">
                    Edit
                </a>
            @endif
            <a href="{{ route('vehicles.index') }}"
                class="bg-gray-500 hover:bg-gray-700 text-white font-sm md:font-base font-bold py-1 md:py-2 px-2 md:px-4 rounded">
                Back
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Vehicle Information</h2>
        <dl class="grid grid-cols-2 gap-4">
            <div>
                <dt class="font-bold">Registration Number:</dt>
                <dd>{{ $vehicle->registration_number }}</dd>
            </div>
            <div>
                <dt class="font-bold">Status:</dt>
                <dd>
                    @if ($vehicle->is_active)
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                    @else
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Inactive</span>
                    @endif
                </dd>
            </div>
            @if ($vehicle->notes)
                <div class="col-span-2">
                    <dt class="font-bold">Notes:</dt>
                    <dd>{{ $vehicle->notes }}</dd>
                </div>
            @endif
        </dl>
    </div>

    @if ($vehicle->trips->count() > 0)
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Trip History</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Trip Number</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Driver</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($vehicle->trips as $trip)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $trip->trip_number }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $trip->driver->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $trip->trip_date->format('Y-m-d') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 py-1 text-xs font-semibold rounded-full
                                    @if ($trip->status->value === 'completed') bg-green-100 text-green-800
                                    @elseif($trip->status->value === 'started') bg-blue-100 text-blue-800
                                    @elseif($trip->status->value === 'in_process') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                        {{ $trip->status_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a href="{{ route('trips.show', $trip) }}"
                                        class="text-[hsl(217,91%,35%)] hover:text-blue-900">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection
