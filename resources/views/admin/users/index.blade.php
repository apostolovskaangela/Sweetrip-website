@extends('layouts.auth')

@section('title', 'Users')

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl md:text-3xl font-bold tracking-tight">Users</h1>
                <p class="text-sm md:text-base text-gray-500">Manage all system users and their roles</p>
            </div>

            @if (auth()->user()->isAdmin())
                <a href="{{ route('users.create') }}"
                    class="inline-flex items-center px-2 md:px-4 md:py-2 bg-[hsl(217,91%,35%)] text-white text-sm md:text-base rounded-lg focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    New User
                </a>
            @endif
        </div>

        {{-- User List --}}
        <div>
            <div class="space-y-4">
                @foreach ($users as $user)
                    <div class="p-6 border border-gray-300 rounded-lg bg-white shadow-sm">
                        <div class="flex flex-col md:flex-row items-start justify-between">
                            <div class="space-y-3 flex-1">
                                <div class="flex flex-col md:flex-row items-start md:items-center gap-3">
                                    <h3 class="text-lg md:text-xl font-semibold">{{ $user->name }}</h3>
                                    <div
                                        class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] md:text-xs font-semibold border border-gray-200
                                    @if ($user->hasRole('admin')) bg-red-100 text-red-700
                                    @elseif($user->hasRole('manager')) bg-blue-100 text-blue-700
                                    @elseif($user->hasRole('driver')) bg-green-100 text-green-700
                                    @else bg-gray-100 text-gray-700 @endif">
                                        {{ ucfirst($user->roles->pluck('name')->first() ?? 'N/A') }}
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-xs md:text-sm mt-2">
                                    <div>
                                        <p class="text-gray-500">Email</p>
                                        <p class="font-medium">{{ $user->email }}</p>
                                    </div>

                                    <div>
                                        <p class="text-gray-500">Manager</p>
                                        <p class="font-medium">{{ $user->manager->name ?? 'N/A' }}</p>
                                    </div>

                                    <div>
                                        <p class="text-gray-500">Created At</p>
                                        <p class="font-medium">{{ $user->created_at->format('Y-m-d') }}</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Actions --}}
                            @if (auth()->user()->isAdmin())
                                <div class="flex gap-2 mt-3 md:mt-0">
                                    <a href="{{ route('users.edit', $user->id) }}"
                                        class="inline-flex items-center justify-center gap-2 rounded-md text-sm font-medium border border-gray-400 bg-background hover:bg-orange-500 hover:text-white h-10 w-10 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 lucide lucide-edit"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M12 20h9"></path>
                                            <path d="M16.5 3.5a2.121 2.121 0 013 3L7 19l-4 1 1-4 12.5-12.5z"></path>
                                        </svg>
                                    </a>

                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this user?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center justify-center gap-2 rounded-md text-sm font-medium border border-gray-400 bg-background hover:bg-red-500 hover:text-white h-10 w-10 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path d="M10 11V17" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M14 11V17" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M4 7H20" stroke-linecap="round" stroke-linejoin="round" />
                                                <path
                                                    d="M6 7H12H18V18C18 19.6569 16.6569 21 15 21H9C7.34315 21 6 19.6569 6 18V7Z"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M9 5C9 3.89543 9.89543 3 11 3H13C14.1046 3 15 3.89543 15 5V7H9V5Z"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>


    </div>
@endsection
