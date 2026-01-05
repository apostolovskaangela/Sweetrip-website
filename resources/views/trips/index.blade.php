@extends('layouts.auth')

@section('title', 'Trips')

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl md:text-3xl font-bold tracking-tight">Trips</h1>
                <p class="text-sm md:text-base text-gray-500">Manage and track all your trips</p>
            </div>

            @if (auth()->user()->isManager() || auth()->user()->isAdmin())
                @can('create', \App\Models\Trip::class)
                    <a href="{{ route('trips.create')}}" id="openDialogBtn"
                        class="inline-flex items-center px-2 md:px-4 md:py-2 bg-[hsl(217,91%,35%)] text-white text-sm md:text-base rounded-lg  focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        New Trip
                    </a>
                @endcan
            @endif
        </div>

        {{-- Trip List --}}
        <div>
            <div class="space-y-4">
                @foreach ($trips as $trip)
                    <div class="p-6 border border-gray-300 rounded-lg bg-white shadow-sm">
    <div class="flex flex-col md:flex-row items-start justify-between">
    <div class="space-y-3 flex-1">
        <div class="flex flex-col md:flex-row items-start md:items-center gap-3">
            <h3 class="text-lg md:text-xl font-semibold">
                {{ $trip->destination_from }} → {{ $trip->destination_to }}
            </h3>
            <div
                class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] md:text-xs font-semibold border-transparent transition-colors
                @if ($trip->status == 'completed') bg-green-100 text-green-700
                @elseif($trip->status == 'scheduled') bg-gray-100 text-gray-700
                @elseif($trip->status == 'in-progress') bg-[hsl(217,91%,35%)] text-white
                @else bg-gray-100 @endif">
                @if ($trip->status == 'not_started')
                    {{ $trip->progress ?? '0%' }}
                @else
                    {{ $trip->status }}
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-3 text-xs md:text-sm">
            {{-- Hidden on small screens --}}
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

        {{-- Hidden on small screens --}}
        <div class="hidden md:flex items-center gap-2 text-xs md:text-sm text-gray-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 lucide lucide-map-pin" fill="none"
                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round">
                <path
                    d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0" />
                <circle cx="12" cy="10" r="3" />
            </svg>
            <span>{{ $trip->mileage }} km</span>
        </div>
    </div>

    <a href="{{ route('trips.show', $trip->id) }}"
        class="inline-flex items-center justify-center gap-2 mt-3 md:mt-0 rounded-md text-sm font-medium border border-gray-400 bg-background hover:bg-orange-500 hover:text-white h-10 w-10 transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 lucide lucide-eye" fill="none" viewBox="0 0 24 24"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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

        <div class="mt-4">
            {{ $trips->links() }}
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
