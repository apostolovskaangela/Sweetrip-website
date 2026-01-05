@extends('layouts.auth')

@section('title', 'Edit Trip')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold">Edit Trip: {{ $trip->trip_number }}</h1>
</div>

<form action="{{ route('trips.update', $trip) }}" method="POST" class="bg-white rounded-lg shadow p-6">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-2 gap-4 mb-4">
        <div>
            <label for="trip_number" class="block text-gray-700 font-bold mb-2">Trip Number *</label>
            <input type="text" name="trip_number" id="trip_number" value="{{ old('trip_number', $trip->trip_number) }}" required
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
            @error('trip_number')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="vehicle_id" class="block text-gray-700 font-bold mb-2">Vehicle *</label>
            <select name="vehicle_id" id="vehicle_id" required
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
                <option value="">Select Vehicle</option>
                @foreach($vehicles as $vehicle)
                    <option value="{{ $vehicle->id }}" {{ old('vehicle_id', $trip->vehicle_id) == $vehicle->id ? 'selected' : '' }}>
                        {{ $vehicle->registration_number }} ({{ $vehicle->make }} {{ $vehicle->model }})
                    </option>
                @endforeach
            </select>
            @error('vehicle_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="driver_id" class="block text-gray-700 font-bold mb-2">Driver *</label>
            <select name="driver_id" id="driver_id" required
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
                <option value="">Select Driver</option>
                @foreach($drivers as $driver)
                    <option value="{{ $driver->id }}" {{ old('driver_id', $trip->driver_id) == $driver->id ? 'selected' : '' }}>
                        {{ $driver->name }}
                    </option>
                @endforeach
            </select>
            @error('driver_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="trip_date" class="block text-gray-700 font-bold mb-2">Trip Date *</label>
            <input type="date" name="trip_date" id="trip_date" value="{{ old('trip_date', $trip->trip_date->format('Y-m-d')) }}" required
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
            @error('trip_date')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="status" class="block text-gray-700 font-bold mb-2">Status</label>
            <select name="status" id="status"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
                @foreach(\App\TripStatus::cases() as $status)
                    <option value="{{ $status->value }}" {{ old('status', $trip->status->value) === $status->value ? 'selected' : '' }}>
                        {{ $status->label() }}
                    </option>
                @endforeach
            </select>
            @error('status')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="a_code" class="block text-gray-700 font-bold mb-2">A-Code</label>
            <input type="text" name="a_code" id="a_code" value="{{ old('a_code', $trip->a_code) }}"
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
            @error('a_code')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="destination_from" class="block text-gray-700 font-bold mb-2">From *</label>
            <input type="text" name="destination_from" id="destination_from" value="{{ old('destination_from', $trip->destination_from) }}" required
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
            @error('destination_from')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="destination_to" class="block text-gray-700 font-bold mb-2">To *</label>
            <input type="text" name="destination_to" id="destination_to" value="{{ old('destination_to', $trip->destination_to) }}" required
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
            @error('destination_to')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="mileage" class="block text-gray-700 font-bold mb-2">Mileage</label>
            <input type="number" step="0.01" name="mileage" id="mileage" value="{{ old('mileage', $trip->mileage) }}"
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
            @error('mileage')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="invoice_number" class="block text-gray-700 font-bold mb-2">Invoice Number</label>
            <input type="text" name="invoice_number" id="invoice_number" value="{{ old('invoice_number', $trip->invoice_number) }}"
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
            @error('invoice_number')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="amount" class="block text-gray-700 font-bold mb-2">Amount</label>
            <input type="number" step="0.01" name="amount" id="amount" value="{{ old('amount', $trip->amount) }}"
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
            @error('amount')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
    </div>

    <div class="mb-4">
        <label for="driver_description" class="block text-gray-700 font-bold mb-2">Driver Description</label>
        <textarea name="driver_description" id="driver_description" rows="3"
                  class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">{{ old('driver_description', $trip->driver_description) }}</textarea>
        @error('driver_description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    <div class="mb-4">
        <label for="admin_description" class="block text-gray-700 font-bold mb-2">Admin Description</label>
        <textarea name="admin_description" id="admin_description" rows="3"
                  class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">{{ old('admin_description', $trip->admin_description) }}</textarea>
        @error('admin_description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    <div class="flex justify-end space-x-4">
        <a href="{{ route('trips.show', $trip) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            Cancel
        </a>
        <button type="submit" class="bg-[hsl(217,91%,35%)]  text-white font-bold py-2 px-4 rounded">
            Update Trip
        </button>
    </div>
</form>
@endsection

