<div>
    <!-- Message -->
    @if($message)
        <div class="mb-4 p-4 rounded-lg {{ $messageType === 'success' ? 'bg-green-50 border border-green-200 text-green-700' : 'bg-red-50 border border-red-200 text-red-700' }}">
            {{ $message }}
        </div>
    @endif

    <!-- Filters -->
    <div class="mb-6 bg-white rounded-xl border p-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search bookings..."
                   class="px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            <select wire:model.live="statusFilter" class="px-4 py-2.5 border border-gray-300 rounded-lg">
                <option value="">All Status</option>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
                <option value="cancelled">Cancelled</option>
            </select>
            <select wire:model.live="roomFilter" class="px-4 py-2.5 border border-gray-300 rounded-lg">
                <option value="">All Rooms</option>
                @foreach($rooms as $room)
                    <option value="{{ $room->id }}">{{ $room->name }}</option>
                @endforeach
            </select>
            <input type="date" wire:model.live="dateFilter" class="px-4 py-2.5 border border-gray-300 rounded-lg">
        </div>
    </div>

    <!-- Bookings Table -->
    <div class="bg-white rounded-xl border overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Room</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Booked By</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date & Time</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Purpose</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($bookings as $booking)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-medium text-gray-800">{{ $booking->room->name }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div>
                                <p class="text-sm text-gray-800">{{ $booking->user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $booking->user->department }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div>
                                <p class="text-sm text-gray-800">{{ $booking->booking_date->format('M d, Y') }}</p>
                                <p class="text-xs text-gray-500">{{ $booking->time_range }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-gray-600 truncate max-w-xs">{{ $booking->purpose }}</p>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs rounded-full font-medium
                                {{ $booking->status === 'approved' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $booking->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                {{ $booking->status === 'rejected' ? 'bg-red-100 text-red-700' : '' }}
                                {{ $booking->status === 'cancelled' ? 'bg-gray-100 text-gray-600' : '' }}">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center space-x-2">
                                @if($booking->isPending())
                                    <button wire:click="approveBooking({{ $booking->id }})"
                                            class="px-3 py-1 text-xs bg-green-50 text-green-700 border border-green-200 rounded hover:bg-green-100">
                                        Approve
                                    </button>
                                    <button wire:click="openRejectModal({{ $booking->id }})"
                                            class="px-3 py-1 text-xs bg-red-50 text-red-700 border border-red-200 rounded hover:bg-red-100">
                                        Reject
                                    </button>
                                @endif
                                @if($booking->isCancellable())
                                    <button wire:click="cancelBooking({{ $booking->id }})"
                                            wire:confirm="Cancel this booking?"
                                            class="px-3 py-1 text-xs bg-gray-50 text-gray-600 border border-gray-200 rounded hover:bg-gray-100">
                                        Cancel
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">No bookings found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $bookings->links() }}
    </div>

    <!-- Reject Modal -->
    @if($rejectingBookingId)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" x-data>
            <div class="bg-white rounded-xl p-6 w-full max-w-md mx-4">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Reject Booking</h3>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Reason (optional)</label>
                    <textarea wire:model="rejectionReason" rows="3" placeholder="Tell the user why this booking was rejected..."
                              class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"></textarea>
                </div>
                <div class="flex space-x-3 justify-end">
                    <button wire:click="$set('rejectingBookingId', null)" class="px-4 py-2 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                        Cancel
                    </button>
                    <button wire:click="rejectBooking" class="px-4 py-2 text-sm bg-red-600 text-white rounded-lg hover:bg-red-700">
                        Reject Booking
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
