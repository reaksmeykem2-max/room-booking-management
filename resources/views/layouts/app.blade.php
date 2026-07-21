<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Room Booking System' }}</title>

    <!-- Tailwind CSS via CDN (for development - use Vite build for production) -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Livewire Styles -->
    @livewireStyles

    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">

    <!-- Navigation -->
    <nav class="bg-white border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo & Main Nav -->
                <div class="flex items-center space-x-8">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <span class="font-bold text-xl text-gray-800">RoomBook</span>
                    </a>

                    <!-- User Nav -->
                    <div class="hidden md:flex items-center space-x-4">
                        <a href="{{ route('book-room') }}" class="px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('book-room') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                            Book Room
                        </a>
                        <a href="{{ route('availability') }}" class="px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('availability') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                            Availability
                        </a>
                        <a href="{{ route('my-bookings') }}" class="px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('my-bookings') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                            My Bookings
                        </a>

                        @if(auth()->user()?->isAdmin())
                            <span class="border-l border-gray-300 h-6"></span>
                            <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.*') ? 'bg-purple-50 text-purple-700' : 'text-purple-600 hover:text-purple-900 hover:bg-purple-50' }}">
                                Admin Panel
                            </a>
                        @endif
                    </div>
                </div>

                <!-- User Menu -->
                <div class="flex items-center space-x-4">
                    @auth
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="flex items-center space-x-2 text-gray-700 hover:text-gray-900">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                    <span class="text-sm font-medium text-blue-700">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                </div>
                                <span class="hidden md:block text-sm">{{ auth()->user()->name }}</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <div x-show="open" @click.away="open = false" x-cloak
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 border">
                                <div class="px-4 py-2 border-b">
                                    <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                                </div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Sign Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-sm text-gray-700 hover:text-gray-900">Login</a>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Mobile Nav -->
        <div class="md:hidden border-t px-4 py-2 space-y-1">
            <a href="{{ route('book-room') }}" class="block px-3 py-2 text-sm rounded-md {{ request()->routeIs('book-room') ? 'bg-blue-50 text-blue-700' : 'text-gray-600' }}">Book Room</a>
            <a href="{{ route('availability') }}" class="block px-3 py-2 text-sm rounded-md {{ request()->routeIs('availability') ? 'bg-blue-50 text-blue-700' : 'text-gray-600' }}">Availability</a>
            <a href="{{ route('my-bookings') }}" class="block px-3 py-2 text-sm rounded-md {{ request()->routeIs('my-bookings') ? 'bg-blue-50 text-blue-700' : 'text-gray-600' }}">My Bookings</a>
            @if(auth()->user()?->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 text-sm rounded-md text-purple-600">Admin Panel</a>
            @endif
        </div>
    </nav>

    <!-- Page Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{ $slot }}
    </main>

    <!-- Livewire Scripts -->
    @livewireScripts
</body>
</html>
