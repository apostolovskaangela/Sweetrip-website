@extends('layouts.auth')

@section('title', 'Driver Dashboard')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div>
        <h1 class="text-xl md:text-3xl font-bold tracking-tight">Driver Dashboard</h1>
        <p class="text-sm md:text-base text-gray-500">Monitor your trips and vehicle status in real-time</p>
    </div>

    {{-- Stats Cards --}}
    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
        <div class="bg-white shadow rounded-lg p-4 relative">
            <div>
                <h3 class="text-sm text-gray-500">Total Trips</h3>
                <p class="text-2xl font-bold text-[hsl(217,91%,35%)]">{{ $stats['total_trips'] }}</p>
            </div>
            <div class="absolute top-4 right-4 w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="lucide lucide-package h-5 w-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z"></path>
                    <path d="M12 22V12"></path>
                    <path d="m3.3 7 7.703 4.734a2 2 0 0 0 1.994 0L20.7 7"></path>
                    <path d="m7.5 4.27 9 5.15"></path>
                </svg>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-4 relative">
            <div>
                <h3 class="text-sm text-gray-500">Completed Trips</h3>
                <p class="text-2xl font-bold text-green-600">{{ $stats['completed_trips'] }}</p>
            </div>
            <div class="absolute top-4 right-4 w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="lucide lucide-check-circle h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M9 12l2 2 4-4"></path>
                    <circle cx="12" cy="12" r="10"></circle>
                </svg>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-4 relative">
            <div>
                <h3 class="text-sm text-gray-500">Pending Trips</h3>
                <p class="text-2xl font-bold text-yellow-600">{{ $stats['pending_trips'] }}</p>
            </div>
            <div class="absolute top-4 right-4 w-10 h-10 rounded-lg bg-yellow-100 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="lucide lucide-clock h-5 w-5 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <path d="M12 6v6l4 2"></path>
                </svg>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-4 relative">
            <div>
                <h3 class="text-sm text-gray-500">Vehicles</h3>
                <p class="text-2xl font-bold text-[hsl(217,91%,35%)]">{{ $totalVehicles ?? 0 }}</p>
            </div>
            <div class="absolute top-4 right-4 w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="lucide lucide-truck h-5 w-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"></path>
                    <path d="M15 18H9"></path>
                    <path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"></path>
                    <circle cx="17" cy="18" r="2"></circle>
                    <circle cx="7" cy="18" r="2"></circle>
                </svg>
            </div>
        </div>
    </div>

    {{-- Recent Trips --}}
    <div class="grid gap-6 md:grid-cols-1">
        <div class="bg-white shadow rounded-lg p-4">
            <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="lucide lucide-package h-5 w-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z"></path>
                    <path d="M12 22V12"></path>
                    <path d="m3.3 7 7.703 4.734a2 2 0 0 0 1.994 0L20.7 7"></path>
                    <path d="m7.5 4.27 9 5.15"></path>
                </svg>
                Recent Trips
            </h3>
            <ul class="space-y-3">
                @forelse($trips as $trip)
                    <li>
                        <a href="{{ route('trips.show', $trip) }}" class="block p-3 border border-gray-300 rounded-lg flex justify-between items-center hover:bg-gray-50 transition">
                            <div>
                                <p class="font-medium">{{ $trip->destination_from }} → {{ $trip->destination_to }}</p>
                                <p class="text-sm text-gray-500">{{ $trip->vehicle->registration_number ?? 'N/A' }}</p>
                            </div>
                            <span class="px-2 py-1 rounded-full text-xs font-medium
                                @if($trip->status->value === 'completed') bg-green-100 text-green-600
                                @elseif($trip->status->value === 'started') bg-blue-100 text-blue-800
                                @elseif($trip->status->value === 'in_process') bg-yellow-100 text-yellow-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ $trip->status_label }}
                            </span>
                        </a>
                    </li>
                @empty
                    <li class="text-center text-gray-500">No trips assigned.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection
