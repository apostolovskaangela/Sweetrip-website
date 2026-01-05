@extends('layouts.auth')

@section('title', 'Create Vehicle')

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:justify-between md:items-center">
    <h1 class="text-2xl md:text-3xl font-bold tracking-tight mb-4 md:mb-0">Add New Vehicle</h1>
    <a href="{{ route('vehicles.index') }}"
        class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-700 text-white text-sm md:text-base rounded-lg">
        Back to Vehicles
    </a>
</div>

<form action="{{ route('vehicles.store') }}" method="POST" class="bg-white rounded-xl shadow p-6 md:p-8 space-y-6">
    @csrf

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="registration_number" class="block text-gray-700 font-semibold mb-2">Registration Number <span class="text-red-500">*</span></label>
            <input type="text" name="registration_number" id="registration_number"
                value="{{ old('registration_number') }}" required
                class="mt-1 block w-full rounded-lg border border-gray-300 shadow-sm px-3 py-2 text-gray-700 focus:outline-none focus:ring-[hsl(217,91%,35%)] focus:border-[hsl(217,91%,35%)]">
            @error('registration_number')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="is_active" class="block text-gray-700 font-semibold mb-2">Active Status</label>
            <select name="is_active" id="is_active"
                class="mt-1 block w-full rounded-lg border border-gray-300 shadow-sm px-3 py-2 text-gray-700 focus:outline-none focus:ring-[hsl(217,91%,35%)] focus:border-[hsl(217,91%,35%)]">
                <option value="1" {{ old('is_active') == '1' ? 'selected' : '' }}>Active</option>
                <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Inactive</option>
            </select>
            @error('is_active')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="notes" class="block text-gray-700 font-semibold mb-2">Notes</label>
            <textarea name="notes" id="notes" rows="1"
                class="mt-1 block w-full rounded-lg border border-gray-300 shadow-sm px-3 py-2 text-gray-700 focus:outline-none focus:ring-[hsl(217,91%,35%)] focus:border-[hsl(217,91%,35%)]">{{ old('notes') }}</textarea>
            @error('notes')
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
    </div>

    <div class="flex flex-col md:flex-row justify-end gap-4">
        <a href="{{ route('vehicles.index') }}"
            class="inline-flex justify-center items-center px-4 py-2 bg-gray-500 hover:bg-gray-700 text-white font-semibold rounded-lg">
            Cancel
        </a>
        <button type="submit"
            class="inline-flex justify-center items-center px-4 py-2 bg-[hsl(217,91%,35%)] hover:bg-[hsl(217,91%,25%)] text-white font-semibold rounded-lg">
            Create Vehicle
        </button>
    </div>
</form>
@endsection
