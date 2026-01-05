@extends('layouts.auth')

@section('title', 'Edit Vehicle')

@section('content')
    <div class="mb-6">
        <h1 class="text-3xl font-bold">Edit Vehicle: {{ $vehicle->registration_number }}</h1>
    </div>

    <form action="{{ route('vehicles.update', $vehicle) }}" method="POST" class="bg-white rounded-lg shadow p-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label for="registration_number" class="block text-gray-700 font-bold mb-2">Registration Number *</label>
                <input type="text" name="registration_number" id="registration_number"
                    value="{{ old('registration_number', $vehicle->registration_number) }}" required
                    class="shadow appearance-none border border-gray-300 rounded w-full py-2 px-3 text-gray-700">
                @error('registration_number')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            @if (auth()->user()->isAdmin())
                <div>
                    <label for="manager_id" class="block text-gray-700 font-semibold mb-2">Assign Manager <span
                            class="text-red-500">*</span></label>
                    <select name="manager_id" id="manager_id" required
                        class="mt-1 block w-full rounded-lg border border-gray-300 shadow-sm px-3 py-2 text-gray-700 focus:outline-none focus:ring-[hsl(217,91%,35%)] focus:border-[hsl(217,91%,35%)]">
                        <option value="">-- Select Manager --</option>
                        @foreach (\App\Models\User::whereRole('manager')->get() as $manager)
                            <option value="{{ $manager->id }}" {{ old('manager_id') == $manager->id ? 'selected' : '' }}>
                                {{ $manager->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('manager_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            @endif

            <div class="col-span-2">
                <label for="notes" class="block text-gray-700 font-bold mb-2">Notes</label>
                <textarea name="notes" id="notes" rows="3"
                    class="shadow appearance-none border border-gray-300 rounded w-full py-2 px-3 text-gray-700">{{ old('notes', $vehicle->notes) }}</textarea>
                @error('notes')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="is_active" class="block text-gray-700 mb-1">Status</label>
                <select name="is_active" id="is_active" class="form-select border-gray-300 rounded-md shadow-sm">
                    <option value="1" {{ old('is_active', $vehicle->is_active ?? true) ? 'selected' : '' }}>Active
                    </option>
                    <option value="0" {{ old('is_active', $vehicle->is_active ?? true) ? '' : 'selected' }}>Inactive
                    </option>
                </select>
            </div>


        </div>

        <div class="flex justify-end space-x-4">
            <a href="{{ route('vehicles.show', $vehicle) }}"
                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Cancel
            </a>
            <button type="submit" class="bg-[hsl(217,91%,35%)] text-white font-bold py-2 px-4 rounded">
                Update Vehicle
            </button>
        </div>
    </form>
@endsection
