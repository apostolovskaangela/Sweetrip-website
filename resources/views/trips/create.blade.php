@extends('layouts.auth')

@section('title', 'Create Trip')

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:justify-between md:items-center">
    <h1 class="text-2xl md:text-3xl font-bold tracking-tight mb-4 md:mb-0">Create New Trip</h1>
    <a href="{{ route('trips.index') }}"
       class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-700 text-white text-sm md:text-base rounded-lg">
        Back to Trips
    </a>
</div>

<form action="{{ route('trips.store') }}" method="POST" class="bg-white rounded-xl shadow p-6 md:p-8 space-y-6">
    @csrf

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="trip_number" class="block text-gray-700 font-semibold mb-2">Trip Number <span class="text-red-500">*</span></label>
            <input type="text" name="trip_number" id="trip_number" value="{{ old('trip_number') }}" required
                   class="mt-1 block w-full rounded-lg border border-gray-300 shadow-sm px-3 py-2 text-gray-700 focus:outline-none focus:ring-[hsl(217,91%,35%)] focus:border-[hsl(217,91%,35%)]">
            @error('trip_number')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="vehicle_id" class="block text-gray-700 font-semibold mb-2">Vehicle <span class="text-red-500">*</span></label>
            <select name="vehicle_id" id="vehicle_id" required
                    class="mt-1 block w-full rounded-lg border border-gray-300 shadow-sm px-3 py-2 text-gray-700 focus:outline-none focus:ring-[hsl(217,91%,35%)] focus:border-[hsl(217,91%,35%)]">
                <option value="">Select Vehicle</option>
                @foreach($vehicles as $vehicle)
                <span>{{$vehicles}}</span>
                    <option value="{{ $vehicle->id }}" {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}>

                        {{ $vehicle->registration_number }} ({{ $vehicle->make }} {{ $vehicle->model }})
                    </option>
                @endforeach
            </select>
            @error('vehicle_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="driver_id" class="block text-gray-700 font-semibold mb-2">Driver <span class="text-red-500">*</span></label>
            <select name="driver_id" id="driver_id" required
                    class="mt-1 block w-full rounded-lg border border-gray-300 shadow-sm px-3 py-2 text-gray-700 focus:outline-none focus:ring-[hsl(217,91%,35%)] focus:border-[hsl(217,91%,35%)]">
                <option value="">Select Driver</option>
                @foreach($drivers as $driver)
                    <option value="{{ $driver->id }}" {{ old('driver_id') == $driver->id ? 'selected' : '' }}>
                        {{ $driver->name }}
                    </option>
                @endforeach
            </select>
            @error('driver_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="trip_date" class="block text-gray-700 font-semibold mb-2">Trip Date <span class="text-red-500">*</span></label>
            <input type="date" name="trip_date" id="trip_date" value="{{ old('trip_date') }}" required
                   class="mt-1 block w-full rounded-lg border border-gray-300 shadow-sm px-3 py-2 text-gray-700 focus:outline-none focus:ring-[hsl(217,91%,35%)] focus:border-[hsl(217,91%,35%)]">
            @error('trip_date')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="a_code" class="block text-gray-700 font-semibold mb-2">A-Code</label>
            <input type="text" name="a_code" id="a_code" value="{{ old('a_code') }}"
                   class="mt-1 block w-full rounded-lg border border-gray-300 shadow-sm px-3 py-2 text-gray-700 focus:outline-none focus:ring-[hsl(217,91%,35%)] focus:border-[hsl(217,91%,35%)]">
            @error('a_code')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="destination_from" class="block text-gray-700 font-semibold mb-2">From <span class="text-red-500">*</span></label>
            <input type="text" name="destination_from" id="destination_from" value="{{ old('destination_from') }}" required
                   class="mt-1 block w-full rounded-lg border border-gray-300 shadow-sm px-3 py-2 text-gray-700 focus:outline-none focus:ring-[hsl(217,91%,35%)] focus:border-[hsl(217,91%,35%)]">
            @error('destination_from')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="destination_to" class="block text-gray-700 font-semibold mb-2">To <span class="text-red-500">*</span></label>
            <input type="text" name="destination_to" id="destination_to" value="{{ old('destination_to') }}" required
                   class="mt-1 block w-full rounded-lg border border-gray-300 shadow-sm px-3 py-2 text-gray-700 focus:outline-none focus:ring-[hsl(217,91%,35%)] focus:border-[hsl(217,91%,35%)]">
            @error('destination_to')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="mileage" class="block text-gray-700 font-semibold mb-2">Mileage</label>
            <input type="number" step="0.01" name="mileage" id="mileage" value="{{ old('mileage') }}"
                   class="mt-1 block w-full rounded-lg border border-gray-300 shadow-sm px-3 py-2 text-gray-700 focus:outline-none focus:ring-[hsl(217,91%,35%)] focus:border-[hsl(217,91%,35%)]">
            @error('mileage')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="invoice_number" class="block text-gray-700 font-semibold mb-2">Invoice Number</label>
            <input type="text" name="invoice_number" id="invoice_number" value="{{ old('invoice_number') }}"
                   class="mt-1 block w-full rounded-lg border border-gray-300 shadow-sm px-3 py-2 text-gray-700 focus:outline-none focus:ring-[hsl(217,91%,35%)] focus:border-[hsl(217,91%,35%)]">
            @error('invoice_number')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="amount" class="block text-gray-700 font-semibold mb-2">Amount</label>
            <input type="number" step="0.01" name="amount" id="amount" value="{{ old('amount') }}"
                   class="mt-1 block w-full rounded-lg border border-gray-300 shadow-sm px-3 py-2 text-gray-700 focus:outline-none focus:ring-[hsl(217,91%,35%)] focus:border-[hsl(217,91%,35%)]">
            @error('amount')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
    </div>

    <div>
        <label for="driver_description" class="block text-gray-700 font-semibold mb-2">Driver Description</label>
        <textarea name="driver_description" id="driver_description" rows="3"
                  class="mt-1 block w-full rounded-lg border border-gray-300 shadow-sm px-3 py-2 text-gray-700 focus:outline-none focus:ring-[hsl(217,91%,35%)] focus:border-[hsl(217,91%,35%)]">{{ old('driver_description') }}</textarea>
        @error('driver_description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="admin_description" class="block text-gray-700 font-semibold mb-2">Admin Description</label>
        <textarea name="admin_description" id="admin_description" rows="3"
                  class="mt-1 block w-full rounded-lg border border-gray-300 shadow-sm px-3 py-2 text-gray-700 focus:outline-none focus:ring-[hsl(217,91%,35%)] focus:border-[hsl(217,91%,35%)]">{{ old('admin_description') }}</textarea>
        @error('admin_description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    <div class="flex flex-col md:flex-row justify-end gap-4">
        <a href="{{ route('trips.index') }}" class="inline-flex justify-center items-center px-4 py-2 bg-gray-500 hover:bg-gray-700 text-white font-semibold rounded-lg">
            Cancel
        </a>
        <button type="submit" class="inline-flex justify-center items-center px-4 py-2 bg-[hsl(217,91%,35%)] hover:bg-[hsl(217,91%,25%)] text-white font-semibold rounded-lg">
            Create Trip
        </button>
        {{-- @if(auth()->user()->hasRole(['admin', 'manager']))
    <a href="{{ route('trips.create') }}" class="btn btn-primary">Add Trip</a>
@endif --}}
    </div>
</form>
@endsection
