
<div x-data="{ open: false }" class="relative">
    <!-- Mobile toggle button -->
    <button @click="open = !open"
        class="md:hidden fixed top-4 left-4 z-50 p-2 rounded-md bg-[hsl(217,91%,35%)] text-white focus:outline-none">
        <!-- Hamburger -->
        <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
        <!-- Close icon -->
        <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>

    <!-- Sidebar -->
    <div :class="open ? 'translate-x-0' : '-translate-x-full'"
         class="fixed md:translate-x-0 top-0 left-0 z-40 w-64 h-full bg-[hsl(217,91%,35%)] p-6 transform transition-transform duration-300 ease-in-out md:relative md:flex md:flex-col md:w-64">

        <!-- Logo -->
        <div class="flex items-center gap-3 mb-10">
            <div class="w-10 h-10 rounded-xl bg-orange-500 text-white flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-truck w-6 h-6 text-sidebar-primary-foreground">
                    <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"></path>
                    <path d="M15 18H9"></path>
                    <path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"></path>
                    <circle cx="17" cy="18" r="2"></circle>
                    <circle cx="7" cy="18" r="2"></circle>
                </svg>
            </div>
            <span class="text-2xl font-bold text-white">Sweetrip</span>
        </div>

        <!-- Navigation -->
        <nav class="flex flex-col gap-3">
            <a href="{{ route('dashboard') }}"
                class="flex items-center gap-2 px-4 py-2 rounded-md text-white hover:text-orange-500 hover:bg-white/20 transition">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-layout-dashboard h-5 w-5">
                    <rect width="7" height="9" x="3" y="3" rx="1"></rect>
                    <rect width="7" height="5" x="14" y="3" rx="1"></rect>
                    <rect width="7" height="9" x="14" y="12" rx="1"></rect>
                    <rect width="7" height="5" x="3" y="16" rx="1"></rect>
                </svg>
                Dashboard
            </a>

            <a href="{{ route('trips.index') }}"
                class="flex items-center gap-2 px-4 py-2 rounded-md text-white hover:text-orange-500 hover:bg-white/20 transition">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-package h-5 w-5">
                    <path
                        d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z">
                    </path>
                    <path d="M12 22V12"></path>
                    <path d="m3.3 7 7.703 4.734a2 2 0 0 0 1.994 0L20.7 7"></path>
                    <path d="m7.5 4.27 9 5.15"></path>
                </svg>
                Trips
            </a>

            @if (auth()->user()->hasRole('ceo') || auth()->user()->hasRole('manager') || auth()->user()->hasRole('admin'))
                <a href="{{ route('vehicles.index') }}"
                    class="flex items-center gap-2 px-4 py-2 rounded-md text-white hover:text-orange-500 hover:bg-white/20 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="lucide lucide-truck h-5 w-5">
                        <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"></path>
                        <path d="M15 18H9"></path>
                        <path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14">
                        </path>
                        <circle cx="17" cy="18" r="2"></circle>
                        <circle cx="7" cy="18" r="2"></circle>
                    </svg>
                    Vehicles
                </a>
            @endif

            @if (auth()->user()->hasRole('admin'))
                <a href="{{ route('users.index') }}"
                    class="flex items-center gap-2 px-4 py-2 rounded-md text-white hover:text-orange-500 hover:bg-white/20 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87M12 12a4 4 0 100-8 4 4 0 000 8z" />
                    </svg>
                    Users
                </a>
            @endif

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full text-left flex items-center gap-2 px-4 py-2 rounded-md text-white hover:text-orange-500 hover:bg-white/20 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="lucide lucide-log-out h-5 w-5">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" x2="9" y1="12" y2="12"></line>
                    </svg>
                    Logout
                </button>
            </form>
        </nav>
    </div>

    <!-- Page overlay for mobile -->
    <div x-show="open" @click="open = false" class="fixed inset-0 backdrop-blur-[1px] bg-white/30 z-30 md:hidden">
    </div>
</div>
