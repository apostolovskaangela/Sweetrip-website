@extends('layouts.auth')

@section('title', 'Trips')

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl md:text-3xl font-bold tracking-tight">Vehicles</h1>
                <p class="text-sm md:text-base text-gray-500">Monitor your fleet status and performance</p>
            </div>

            @if (auth()->user()->isManager() || auth()->user()->isAdmin())
                @can('create', \App\Models\Vehicle::class)
                    <a href="{{ route('vehicles.create') }}" id="openDialogBtn"
                        class="inline-flex items-center px-2 md:px-4 md:py-2 bg-[hsl(217,91%,35%)] text-white text-sm md:text-base rounded-lg focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        New Vehicle
                    </a>
                @endcan
            @endif
        </div>

        {{-- Trip List --}}
        <div>
            <div class="space-y-4">
                @foreach ($vehicles as $vehicle)
                    <div class="p-6 border border-gray-300 rounded-lg bg-white shadow-sm">
                        <div class="flex flex-col md:flex-row items-start justify-between">
                            <div class="space-y-3 flex-1">
                                <div class="flex flex-col md:flex-row items-start md:items-center gap-3">
                                    <h3 class=" flex gap-3 items-center text-lg md:text-xl font-semibold">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-truck h-5 w-5 text-primary">
                                            <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"></path>
                                            <path d="M15 18H9"></path>
                                            <path
                                                d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14">
                                            </path>
                                            <circle cx="17" cy="18" r="2"></circle>
                                            <circle cx="7" cy="18" r="2"></circle>
                                        </svg>
                                        Registration Number {{ $vehicle->registration_number }}
                                    </h3>
                                    <span
                                        class="px-2 py-1 rounded-full text-xs font-medium
                                        @if ($vehicle->is_active) bg-green-100 text-green-600
                                        @else bg-gray-100 text-gray-500 @endif">
                                        {{ $vehicle->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>

                                <div class="text-xs md:text-sm">
                                    {{-- Hidden on small screens --}}

                                    <div>
                                        <p class="text-gray-500">Notes</p>
                                        <p class="font-medium">{{ $vehicle->notes }}</p>
                                    </div>
                                </div>

                            </div>

                            <a href="{{ route('vehicles.show', $vehicle->id) }}"
                                class="inline-flex items-center justify-center gap-2 mt-3 md:mt-0 rounded-md text-sm font-medium border border-gray-400 bg-background hover:bg-orange-500 hover:text-white h-10 w-10 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 lucide lucide-eye" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path
                                        d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                            </a>
                        </div>

                    </div>
                @endforeach
            </div>

        </div>


    </div>

    <script>
        const openDialog = document.getElementById('openDialogBtn');
        const closeDialog = document.getElementById('closeDialogBtn');
        const dialog = document.getElementById('createDialog');

        openDialog?.addEventListener('click', () => dialog.classList.remove('hidden'));
        closeDialog?.addEventListener('click', () => dialog.classList.add('hidden'));
        dialog?.addEventListener('click', (e) => {
            if (e.target === dialog) dialog.classList.add('hidden');
        });
    </script>
@endsection
