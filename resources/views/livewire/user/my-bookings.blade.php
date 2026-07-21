<div>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">My Bookings</h2>
        <p class="text-gray-500 mt-1">View and manage your room bookings</p>
    </div>

    <!-- Message -->
    @if($message)
        <div class="mb-4 p-4 rounded-lg {{ $messageType === 'success' ? 'bg-green-50 border border-green-200 text-green-700' : 'bg-red-50 border border-red-200 text-red-700' }}">
            {{ $message }}
        </div>
    @endif

    <!-- Filters -->
    <div class="mb-6 flex flex-col sm:flex-row gap-3">
        <div class="flex-1">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by room or purpose..."
                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>
        <select wire:model.live="statusFilter" class="px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            <option value="">All Status</option>
            <option value="pending">Pending</option>
            <option value="approved">Approved</option>
            <option value="rejected">Rejected</option>
            <option value="cancelled">Cancelled</option>
            <option value="completed">Completed</option>
        </select>
    </div>

    <!-- Bookings List -->
    <div class="space-y-3">
        @forelse($bookings as $booking)
            <div class="bg-white border rounded-xl p-5 hover:shadow-sm transition">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3">
                            <h3 class="font-semibold text-gray-800">{{ $booking->room->name }}</h3>
                            <span class="px-2 py-0.5 text-xs rounded-full font-medium
                                {{ $booking->status === 'approved' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $booking->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                {{ $booking->status === 'rejected' ? 'bg-red-100 text-red-700' : '' }}
                                {{ $booking->status === 'cancelled' ? 'bg-gray-100 text-gray-600' : '' }}
                                {{ $booking->status === 'completed' ? 'bg-blue-100 text-blue-700' : '' }}">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">
                            <span class="font-medium">{{ $booking->booking_date->format('D, M d Y') }}</span>
                            &middot; {{ $booking->time_range }}
                        </p>
                        <p class="text-sm text-gray-500 mt-0.5">{{ $booking->purpose }}</p>
                        @if($booking->status === 'rejected' && $booking->rejection_reason)
                            <p class="text-sm text-red-600 mt-1">Reason: {{ $booking->rejection_reason }}</p>
                        @endif
                    </div>
                    <div class="flex items-center space-x-2">
                        @if($booking->isCancellable())
                            <button wire:click="cancelBooking({{ $booking->id }})"
                                    wire:confirm="Are you sure you want to cancel this booking?"
                                    class="px-3 py-1.5 text-sm bg-red-50 text-red-600 border border-red-200 rounded-lg hover:bg-red-100 transition">
                                Cancel
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-12 bg-white rounded-xl border">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p class="text-gray-500">No bookings found.</p>
                <a href="{{ route('book-room') }}" class="inline-block mt-3 text-blue-600 hover:text-blue-800 text-sm font-medium">Book a room now &rarr;</a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $bookings->links() }}
    </div>
</div>
