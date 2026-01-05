@extends('layouts.auth')

@section('title', 'Create User')

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:justify-between md:items-center">
    <h1 class="text-2xl md:text-3xl font-bold tracking-tight mb-4 md:mb-0">Add New User</h1>
    <a href="{{ route('users.index') }}"
        class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-700 text-white text-sm md:text-base rounded-lg">
        Back to Users
    </a>
</div>

<form action="{{ route('users.store') }}" method="POST" class="bg-white rounded-xl shadow p-6 md:p-8 space-y-6">
    @csrf

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="name" class="block text-gray-700 font-semibold mb-2">Name <span class="text-red-500">*</span></label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                class="mt-1 block w-full rounded-lg border border-gray-300 shadow-sm px-3 py-2 text-gray-700 focus:outline-none focus:ring-[hsl(217,91%,35%)] focus:border-[hsl(217,91%,35%)]">
            @error('name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="block text-gray-700 font-semibold mb-2">Email <span class="text-red-500">*</span></label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                class="mt-1 block w-full rounded-lg border border-gray-300 shadow-sm px-3 py-2 text-gray-700 focus:outline-none focus:ring-[hsl(217,91%,35%)] focus:border-[hsl(217,91%,35%)]">
            @error('email')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="block text-gray-700 font-semibold mb-2">Password <span class="text-red-500">*</span></label>
            <input type="password" name="password" id="password" required
                class="mt-1 block w-full rounded-lg border border-gray-300 shadow-sm px-3 py-2 text-gray-700 focus:outline-none focus:ring-[hsl(217,91%,35%)] focus:border-[hsl(217,91%,35%)]">
            @error('password')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="role" class="block text-gray-700 font-semibold mb-2">Role <span class="text-red-500">*</span></label>
            <select name="role" id="role" required
                class="mt-1 block w-full rounded-lg border border-gray-300 shadow-sm px-3 py-2 text-gray-700 focus:outline-none focus:ring-[hsl(217,91%,35%)] focus:border-[hsl(217,91%,35%)]">
                <option value="">-- Select Role --</option>
                @foreach($roles as $role)
                    <option value="{{ $role }}" {{ old('role') == $role ? 'selected' : '' }}>
                        {{ ucfirst($role) }}
                    </option>
                @endforeach
            </select>
            @error('role')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="flex flex-col md:flex-row justify-end gap-4">
        <a href="{{ route('users.index') }}"
            class="inline-flex justify-center items-center px-4 py-2 bg-gray-500 hover:bg-gray-700 text-white font-semibold rounded-lg">
            Cancel
        </a>
        <button type="submit"
            class="inline-flex justify-center items-center px-4 py-2 bg-[hsl(217,91%,35%)] hover:bg-[hsl(217,91%,25%)] text-white font-semibold rounded-lg">
            Create User
        </button>
    </div>
</form>
@endsection
