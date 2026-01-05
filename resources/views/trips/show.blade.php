@extends('layouts.auth')

@section('title', 'Trip Details')

@section('content')
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 gap-4">
        <h1 class="text-2xl sm:text-3xl font-bold tracking-tight">Trip: {{ $trip->trip_number }}</h1>
        <div class="flex flex-wrap gap-2">
            @if (auth()->user()->isManager() || auth()->user()->isAdmin())
                @can('update', $trip)
                    <a href="{{ route('trips.edit', $trip) }}"
                        class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-[hsl(217,91%,35%)] text-white font-semibold rounded shadow-sm text-sm sm:text-base">
                        Edit
                    </a>
                @endcan
            @endif
            <a href="{{ route('trips.index') }}"
                class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-gray-500 hover:bg-gray-700 text-white font-semibold rounded shadow-sm text-sm sm:text-base">
                Back
            </a>
        </div>
    </div>

    <div class="space-y-6">
        <div class="p-6 bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="flex flex-col md:flex-row md:justify-between gap-4">
                <div class="flex-1 space-y-3">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4">
                        <h2 class="text-lg sm:text-xl font-semibold">{{ $trip->destination_from }} →
                            {{ $trip->destination_to }}</h2>
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs sm:text-sm font-semibold
                        @if ($trip->status->value === 'completed') bg-green-100 text-green-700
                        @elseif($trip->status->value === 'started') bg-blue-100 text-blue-700
                        @elseif($trip->status->value === 'in_process') bg-yellow-100 text-yellow-700
                        @else bg-gray-100 text-gray-700 @endif">
                            {{ $trip->status_label }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 text-sm sm:text-base">
                        <div class="hidden md:block">
                            <p class="text-gray-500">Driver</p>
                            <p class="font-medium">{{ $trip->driver->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Vehicle</p>
                            <p class="font-medium">{{ $trip->vehicle->registration_number ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Trip Number</p>
                            <p class="font-medium">{{ $trip->trip_number }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Date</p>
                            <p class="font-medium">{{ $trip->trip_date->format('Y-m-d') }}</p>
                        </div>
                    </div>

                    <div class="hidden md:flex items-center gap-2 text-sm text-gray-500 mt-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 lucide lucide-map-pin" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path
                                d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0" />
                            <circle cx="12" cy="10" r="3" />
                        </svg>
                        <span>{{ $trip->mileage ?? '0' }} km</span>
                    </div>
                </div>


            </div>
        </div>

        @if ($trip->stops->count() > 0)
            <div class="p-6 bg-white rounded-lg shadow-sm border border-gray-200">
                <h2 class="text-lg sm:text-xl font-semibold mb-2">Additional Stops</h2>
                <ul class="list-disc list-inside space-y-1 text-sm sm:text-base">
                    @foreach ($trip->stops as $stop)
                        <li>{{ $stop->stop_order }}. {{ $stop->destination }} @if ($stop->notes)
                                - {{ $stop->notes }}
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if ($trip->driver_description)
            <div class="p-6 bg-white rounded-lg shadow-sm border border-gray-200">
                <h2 class="text-lg sm:text-xl font-semibold mb-2">Driver Description</h2>
                <p class="text-gray-700 text-sm sm:text-base">{{ $trip->driver_description }}</p>
            </div>
        @endif

        @if ($trip->admin_description && (auth()->user()->isManager() || auth()->user()->isAdmin()))
            <div class="p-6 bg-white rounded-lg shadow-sm border border-gray-200">
                <h2 class="text-lg sm:text-xl font-semibold mb-2">Admin Description</h2>
                <p class="text-gray-700 text-sm sm:text-base">{{ $trip->admin_description }}</p>
            </div>
        @endif

        @if ($trip->invoice_number || $trip->amount)
            <div class="p-6 bg-white rounded-lg shadow-sm border border-gray-200">
                <h2 class="text-lg sm:text-xl font-semibold mb-2">Financial Information</h2>
                <dl class="space-y-2 text-sm sm:text-base">
                    @if ($trip->invoice_number)
                        <dt class="font-bold">Invoice Number:</dt>
                        <dd>{{ $trip->invoice_number }}</dd>
                    @endif
                    @if ($trip->amount)
                        <dt class="font-bold">Amount:</dt>
                        <dd>{{ number_format($trip->amount, 2) }} SEK</dd>
                    @endif
                </dl>
            </div>
        @endif

        @if ($trip->cmr)
    <div class="p-6 bg-white rounded-lg shadow-sm border border-gray-200 mt-6">
        <h2 class="text-lg sm:text-xl font-semibold mb-4">CMR Document</h2>

        @php
            $extension = pathinfo($trip->cmr, PATHINFO_EXTENSION);
        @endphp

        @if (in_array($extension, ['jpg', 'jpeg', 'png']))
            <div x-data="{ open: false }">
                <!-- Thumbnail -->
                <img src="{{ $trip->cmr_url }}" alt="CMR Image"
                     class="max-w-md rounded shadow cursor-pointer"
                     @click="open = true">

                <!-- Modal -->
                <div x-show="open"
                     style="display: none;"
                     class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-70"
                     @click.self="open = false">
                    <div class="relative p-4">
                        <img src="{{ $trip->cmr_url }}" alt="CMR Image" class="max-h-[80vh] rounded shadow-lg">
                        <button @click="open = false"
                                class="absolute top-2 right-2 text-white text-2xl font-bold">&times;</button>
                    </div>
                </div>
            </div>
        @elseif($extension === 'pdf')
            <iframe src="{{ $trip->cmr_url }}" class="w-full h-96 border rounded"></iframe>
        @else
            <a href="{{ $trip->cmr_url }}" class="text-[hsl(217,91%,35%)] underline" target="_blank">View CMR File</a>
        @endif
    </div>
@endif




        @if (auth()->user()->isDriver() && $trip->driver_id === auth()->id())
    <div class="p-6 bg-white rounded-lg shadow-sm border border-gray-200">
        <h2 class="text-lg sm:text-xl font-semibold mb-4">Update Status</h2>

        {{-- Show error if missing CMR --}}
        @if ($errors->has('cmr'))
            <div class="bg-red-100 text-red-700 border border-red-400 rounded p-3 mb-3">
                {{ $errors->first('cmr') }}
            </div>
        @endif

        <form action="{{ route('drivers.trips.status', $trip) }}" method="POST"
              class="flex flex-col sm:flex-row gap-2 sm:items-center">
            @csrf

            <select name="status" id="status-select"
                class="shadow appearance-none border rounded py-2 px-3 text-gray-700 flex-1">
                @foreach (\App\TripStatus::cases() as $status)
                    @if($status->value === 'completed' && empty($trip->cmr))
                        {{-- Disable "completed" if no CMR attached --}}
                        <option value="{{ $status->value }}" disabled>
                            {{ $status->label() }} (attach CMR first)
                        </option>
                    @else
                        <option value="{{ $status->value }}" {{ $trip->status === $status ? 'selected' : '' }}>
                            {{ $status->label() }}
                        </option>
                    @endif
                @endforeach
            </select>

            <button type="submit"
                class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded w-full sm:w-auto">
                Update Status
            </button>
        </form>
    </div>

    <div class="p-6 bg-white rounded-lg shadow-sm border border-gray-200">
        <h2 class="text-lg sm:text-xl font-semibold mb-4">Attach CMR</h2>

        <form action="{{ route('trips.cmr', $trip) }}" method="POST" enctype="multipart/form-data"
              class="flex flex-col gap-2">
            @csrf

            <input type="file" name="cmr"
                   class="shadow appearance-none border rounded py-2 px-3 text-gray-700" required>

            <button type="submit"
                class="bg-[hsl(217,91%,35%)] text-white font-bold py-2 px-4 rounded">
                Upload CMR
            </button>
        </form>

        @if ($trip->cmr)
            <p class="text-sm text-green-600 mt-2">CMR already attached.</p>
        @endif
        @if ($errors->has('cmr'))
    <div class="text-red-600 text-sm mb-2">{{ $errors->first('cmr') }}</div>
@endif
    </div>
@endif

    </div>
@endsection
