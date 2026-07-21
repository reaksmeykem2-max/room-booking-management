<div>
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-xl border p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Today's Bookings</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['bookings_today'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Pending Approvals</p>
                    <p class="text-3xl font-bold {{ $stats['pending_approvals'] > 0 ? 'text-yellow-600' : 'text-gray-800' }} mt-1">{{ $stats['pending_approvals'] }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">This Week</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['bookings_this_week'] }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Active Rooms</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['total_rooms'] }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Pending Approvals -->
        <div class="bg-white rounded-xl border">
            <div class="p-5 border-b">
                <h3 class="font-semibold text-gray-800">Pending Approvals</h3>
            </div>
            <div class="divide-y max-h-96 overflow-y-auto">
                @forelse($pendingBookings as $booking)
                    <div class="p-4 hover:bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-medium text-gray-800">{{ $booking->room->name }}</p>
                                <p class="text-sm text-gray-500">{{ $booking->user->name }} &middot; {{ $booking->booking_date->format('M d') }} &middot; {{ $booking->time_range }}</p>
                                <p class="text-sm text-gray-500">{{ $booking->purpose }}</p>
                            </div>
                            <div class="flex space-x-2">
                                <button wire:click="$dispatch('approve-booking', { id: {{ $booking->id }} })"
                                        class="p-2 text-green-600 hover:bg-green-50 rounded-lg" title="Approve">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                </button>
                                <button wire:click="$dispatch('reject-booking', { id: {{ $booking->id }} })"
                                        class="p-2 text-red-600 hover:bg-red-50 rounded-lg" title="Reject">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-400">
                        <p>No pending approvals</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Today's Bookings -->
        <div class="bg-white rounded-xl border">
            <div class="p-5 border-b">
                <h3 class="font-semibold text-gray-800">Today's Schedule</h3>
            </div>
            <div class="divide-y max-h-96 overflow-y-auto">
                @forelse($todayBookings as $booking)
                    <div class="p-4 hover:bg-gray-50">
                        <div class="flex items-center space-x-3">
                            <div class="text-center bg-blue-50 rounded-lg px-3 py-1.5">
                                <p class="text-xs text-blue-600 font-medium">{{ substr($booking->start_time, 0, 5) }}</p>
                                <p class="text-xs text-blue-400">{{ substr($booking->end_time, 0, 5) }}</p>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">{{ $booking->room->name }}</p>
                                <p class="text-sm text-gray-500">{{ $booking->user->name }} &middot; {{ $booking->purpose }}</p>
                            </div>
                            <span class="ml-auto px-2 py-0.5 text-xs rounded-full font-medium
                                {{ $booking->status === 'approved' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-400">
                        <p>No bookings today</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
