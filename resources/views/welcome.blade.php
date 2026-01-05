@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-hero">
        <div class="container mx-auto px-4 py-16">
            <!-- Hero Section -->
            <div class="max-w-4xl mx-auto text-center mb-16">
                <h1 class="text-5xl md:text-7xl font-bold text-white mb-6 leading-tight">
                    Fleet Management<br>
                    <span class="text-accent text-orange-500">Made Simple</span>
                </h1>
                <p class="text-xl text-white/90 mb-8 max-w-2xl mx-auto">
                    Plan trips, track drivers in real-time, and optimize your logistics operations with Sweetrip's
                    comprehensive fleet management platform.
                </p>
                <div class="flex gap-4 justify-center">
                    <a href="{{ route('login') }}">
                        <button
                            class="inline-flex items-center justify-center gap-2 h-11 rounded-md px-8 text-sm font-medium bg-white hover:bg-gray-200 transition-colors duration-300 ease-in-out focus:outline-none">
                            Start Free Trial
                        </button>
                    </a>

                </div>
            </div>

            <!-- Features Grid -->
            <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                <!-- Feature 1 -->
                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 border border-white/20">
                    <div class="w-14 h-14 rounded-xl bg-gray-300 text-orange-500 flex items-center justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-map-pin w-7 h-7 text-accent">
                            <path
                                d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0">
                            </path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Real-Time Tracking</h3>
                    <p class="text-white/80">
                        Monitor your entire fleet on a live map. Know exactly where every vehicle is, every moment.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 border border-white/20">
                    <div class="w-14 h-14 rounded-xl bg-gray-300 text-orange-500 flex items-center justify-center mb-4"><svg
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-trending-up w-7 h-7 text-accent">
                            <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline>
                            <polyline points="16 7 22 7 22 13"></polyline>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Smart Analytics</h3>
                    <p class="text-white/80">
                        Data-driven insights to optimize routes, reduce costs, and improve efficiency.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 border border-white/20">
                    <div class="w-14 h-14 rounded-xl bg-gray-300 text-orange-500 flex items-center justify-center mb-4"><svg
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-shield w-7 h-7 text-accent">
                            <path
                                d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z">
                            </path>
                        </svg></div>
                    <h3 class="text-xl font-bold text-white mb-3">Enterprise Security</h3>
                    <p class="text-white/80">
                        Bank-level security to protect your fleet data and ensure compliance.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
