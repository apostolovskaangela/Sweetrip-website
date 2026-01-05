@extends('layouts.auth')

@section('title', 'Add New User')

@section('content')
    <div class="mb-6">
        <h1 class="text-3xl font-bold">Edit User</h1>
    </div>

    <form action="{{ route('users.update', $user) }}" method="POST" class="bg-white rounded-lg shadow p-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label for="name" class="block text-gray-700 font-bold mb-2">Name *</label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                    class="shadow appearance-none border border-gray-300 rounded w-full py-2 px-3 text-gray-700">
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block text-gray-700 font-bold mb-2">Email *</label>
                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                    class="shadow appearance-none border border-gray-300 rounded w-full py-2 px-3 text-gray-700">
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="role" class="block text-gray-700 font-bold mb-2">Role *</label>
                <select name="role" id="role" required
                    class="shadow appearance-none border border-gray-300 rounded w-full py-2 px-3 text-gray-700">
                    @foreach ($roles as $roleName)
                        <option value="{{ $roleName }}" {{ old('role', $user->role) === $roleName ? 'selected' : '' }}>
                            {{ ucfirst($roleName) }}
                        </option>
                    @endforeach
                </select>
                @error('role')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-gray-700 font-bold mb-2">Password *</label>
                <input type="password" name="password" id="password" placeholder="Leave blank to keep current"
                    class="shadow appearance-none border border-gray-300 rounded w-full py-2 px-3 text-gray-700">
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex justify-end space-x-4">
            <a href="{{ route('users.index') }}"
                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Cancel
            </a>
            <button type="submit" class="bg-[hsl(217,91%,35%)] text-white font-bold py-2 px-4 rounded">
                Save User
            </button>
        </div>
    </form>
@endsection
