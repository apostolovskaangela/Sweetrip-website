@extends('layouts.auth')

@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <div>
            <h1 class="text-xl md:text-3xl font-bold tracking-tight">Dashboard</h1>
            <p class="text-sm md:text-base text-gray-500">Monitor your fleet operations in real-time</p>
        </div>

        {{-- Stats Cards --}}
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
            {{-- Distance Today --}}
            <div class="bg-white shadow rounded-lg p-4 relative">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-sm text-gray-500">Distance Today</h3>
                        <p class="text-2xl font-bold">{{ number_format($distanceToday, 0) }} km</p>

                    </div>
                    <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center"><svg
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-map-pin h-5 w-5 text-primary">
                            <path
                                d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0">
                            </path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg></div>

                </div>
            </div>

            <div class="bg-white shadow rounded-lg p-4 relative">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-sm text-gray-500">Active Trips</h3>
                        <p class="text-2xl font-bold">{{ $activeTrips }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center"><svg
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-package h-5 w-5 text-primary">
                            <path
                                d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z">
                            </path>
                            <path d="M12 22V12"></path>
                            <path d="m3.3 7 7.703 4.734a2 2 0 0 0 1.994 0L20.7 7"></path>
                            <path d="m7.5 4.27 9 5.15"></path>
                        </svg></div>
                </div>
            </div>

            {{-- Efficiency --}}
            <div class="bg-white shadow rounded-lg p-4 relative">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-sm text-gray-500">Efficiency</h3>
                        <p class="text-2xl font-bold">{{ $efficiency }}%</p>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center"><svg
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-truck h-5 w-5 text-primary">
                            <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"></path>
                            <path d="M15 18H9"></path>
                            <path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14">
                            </path>
                            <circle cx="17" cy="18" r="2"></circle>
                            <circle cx="7" cy="18" r="2"></circle>
                        </svg></div>
                </div>
            </div>

            <div class="bg-white shadow rounded-lg p-4 relative">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-sm text-gray-500">Total vehicles</h3>
                        <p class="text-2xl font-bold">{{ $totalVehicles }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center"><svg
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-truck h-5 w-5 text-primary">
                            <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"></path>
                            <path d="M15 18H9"></path>
                            <path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14">
                            </path>
                            <circle cx="17" cy="18" r="2"></circle>
                            <circle cx="7" cy="18" r="2"></circle>
                        </svg></div>
                </div>
            </div>
        </div>

        {{-- Recent Trips & Vehicle Status --}}
        <div class="grid gap-6 md:grid-cols-2">
            {{-- Recent Trips --}}
            <div class="bg-white shadow rounded-lg p-4">
                <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-package h-5 w-5 text-primary">
                        <path
                            d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z">
                        </path>
                        <path d="M12 22V12"></path>
                        <path d="m3.3 7 7.703 4.734a2 2 0 0 0 1.994 0L20.7 7"></path>
                        <path d="m7.5 4.27 9 5.15"></path>
                    </svg>
                    Recent Trips
                </h3>
                <ul class="space-y-3">
                    @foreach ($trips as $trip)
                        <li>
                            <a href="{{ route('trips.show', $trip->id) }}"
                                class="block p-3 border border-gray-300 rounded-lg flex justify-between items-center hover:bg-gray-50 transition">
                                <div>
                                    <p class="font-medium">{{ $trip->destination_from }} → {{ $trip->destination_to }}</p>
                                    <p class="text-sm text-gray-500">{{ $trip->driver->name ?? 'N/A' }}</p>
                                </div>
                                <span
                                    class="px-2 py-1 rounded-full text-xs font-medium
                    @if ($trip->status == 'completed') bg-green-100 text-green-600
                    @elseif($trip->status == 'scheduled') bg-gray-100 text-gray-600
                    @else bg-[hsl(217,91%,35%)] text-white @endif">
                                    @if ($trip->status == 'not_started')
                                        {{ $trip->progress ?? '0%' }}
                                    @else
                                        {{ $trip->status }}
                                    @endif
                                </span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Vehicle Status --}}
            <div class="bg-white shadow rounded-lg p-4">
                <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-truck h-5 w-5 text-primary">
                        <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"></path>
                        <path d="M15 18H9"></path>
                        <path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"></path>
                        <circle cx="17" cy="18" r="2"></circle>
                        <circle cx="7" cy="18" r="2"></circle>
                    </svg>
                    Vehicle Status
                </h3>

                <ul class="space-y-3">
                    @foreach ($vehicles as $vehicle)
                        <li>
                            <a href="{{ route('vehicles.show', $vehicle->id) }}"
                                class="block p-3 border border-gray-300 rounded-lg flex justify-between items-center hover:bg-gray-50 transition">
                                <div>
                                    <p class="font-medium">{{ $vehicle->registration_number ?? 'N/A' }}</p>
                                    <p class="text-sm text-gray-500">{{ $vehicle->notes ?? 'No notes available' }}</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    {{-- <span class="text-sm">{{ $vehicle->fuel_percentage ?? '100%' }} fuel</span> --}}
                                    <span
                                        class="px-2 py-1 rounded-full text-xs font-medium
                        @if ($vehicle->is_active) bg-green-100 text-green-600
                        @else bg-gray-100 text-gray-500 @endif">
                                        {{ $vehicle->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>

            </div>
        </div>
    </div>
@endsection
