<div>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Book a Room</h2>
        <p class="text-gray-500 mt-1">Select a room, pick a time, and you're done!</p>
    </div>

    <!-- Success Message -->
    @if($successMessage)
        <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4 flex items-center space-x-3">
            <svg class="w-6 h-6 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-green-700 font-medium">{{ $successMessage }}</span>
        </div>
    @endif

    <!-- Error Message -->
    @if($errorMessage)
        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex items-center space-x-3">
                <svg class="w-6 h-6 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-red-700">{{ $errorMessage }}</span>
            </div>

            <!-- Suggestions -->
            @if(count($suggestions) > 0)
                <div class="mt-3 pl-9">
                    <p class="text-sm text-gray-600 font-medium mb-2">Available time slots:</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($suggestions as $suggestion)
                            <button wire:click="useSuggestion('{{ $suggestion['start_time'] }}', '{{ $suggestion['end_time'] }}')"
                                    class="px-3 py-1.5 text-sm bg-blue-50 text-blue-700 border border-blue-200 rounded-md hover:bg-blue-100 transition">
                                {{ $suggestion['label'] }}
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    @endif

    <!-- Step Indicators -->
    <div class="flex items-center mb-8 space-x-4">
        <div class="flex items-center {{ $step >= 1 ? 'text-blue-600' : 'text-gray-400' }}">
            <span class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-medium {{ $step >= 1 ? 'bg-blue-100 text-blue-700' : 'bg-gray-100' }}">1</span>
            <span class="ml-2 text-sm font-medium">Select Room</span>
        </div>
        <div class="flex-1 h-0.5 {{ $step >= 2 ? 'bg-blue-200' : 'bg-gray-200' }}"></div>
        <div class="flex items-center {{ $step >= 2 ? 'text-blue-600' : 'text-gray-400' }}">
            <span class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-medium {{ $step >= 2 ? 'bg-blue-100 text-blue-700' : 'bg-gray-100' }}">2</span>
            <span class="ml-2 text-sm font-medium">Pick Time</span>
        </div>
        <div class="flex-1 h-0.5 {{ $step >= 3 ? 'bg-blue-200' : 'bg-gray-200' }}"></div>
        <div class="flex items-center {{ $step >= 3 ? 'text-blue-600' : 'text-gray-400' }}">
            <span class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-medium {{ $step >= 3 ? 'bg-blue-100 text-blue-700' : 'bg-gray-100' }}">3</span>
            <span class="ml-2 text-sm font-medium">Confirm</span>
        </div>
    </div>

    <!-- Step 1: Select Room -->
    <div class="{{ $step === 1 ? '' : 'hidden' }}">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($rooms as $room)
                <div wire:click="$set('room_id', '{{ $room->id }}')"
                     class="cursor-pointer border-2 rounded-xl p-5 transition hover:shadow-md
                            {{ $room_id == $room->id ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-blue-300' }}">
                    <h3 class="font-semibold text-lg text-gray-800">{{ $room->name }}</h3>
                    <div class="mt-2 space-y-1">
                        @if($room->location)
                            <p class="text-sm text-gray-500 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                {{ $room->location }}{{ $room->floor ? ', Floor ' . $room->floor : '' }}
                            </p>
                        @endif
                        <p class="text-sm text-gray-500 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Capacity: {{ $room->capacity }}
                        </p>
                    </div>
                    @if($room->requires_approval)
                        <span class="inline-block mt-2 px-2 py-0.5 text-xs bg-yellow-100 text-yellow-700 rounded">Requires Approval</span>
                    @else
                        <span class="inline-block mt-2 px-2 py-0.5 text-xs bg-green-100 text-green-700 rounded">Auto-Approve</span>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <!-- Step 2: Pick Date & Time -->
    <div class="{{ $step === 2 ? '' : 'hidden' }}">
        <div class="bg-white rounded-xl border p-6">
            @if($selectedRoom)
                <div class="mb-4 flex items-center justify-between">
                    <div>
                        <h3 class="font-semibold text-gray-800">{{ $selectedRoom->name }}</h3>
                        <p class="text-sm text-gray-500">Select a date and available time slot</p>
                    </div>
                    <button wire:click="$set('step', 1)" class="text-sm text-blue-600 hover:text-blue-800">Change room</button>
                </div>
            @endif

            <!-- Date Picker -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                <input type="date" wire:model.live="booking_date"
                       min="{{ date('Y-m-d') }}"
                       class="w-full md:w-64 px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @error('booking_date') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
            </div>

            <!-- Time Slots Grid -->
            @if(count($timeSlots) > 0)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">Available Time Slots</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-3">
                        @foreach($timeSlots as $slot)
                            @if($slot['available'])
                                <button wire:click="selectTimeSlot('{{ $slot['start_time'] }}', '{{ $slot['end_time'] }}')"
                                        class="px-4 py-3 rounded-lg border-2 text-center transition
                                               {{ $start_time === $slot['start_time'] ? 'border-blue-500 bg-blue-50 text-blue-700' : 'border-green-200 bg-green-50 text-green-700 hover:border-green-400' }}">
                                    <span class="text-sm font-medium">{{ $slot['label'] }}</span>
                                    <p class="text-xs mt-0.5">Available</p>
                                </button>
                            @else
                                <div class="px-4 py-3 rounded-lg border-2 border-gray-200 bg-gray-50 text-center opacity-60 cursor-not-allowed">
                                    <span class="text-sm font-medium text-gray-500">{{ $slot['label'] }}</span>
                                    <p class="text-xs mt-0.5 text-red-500">Booked</p>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @elseif($booking_date)
                <p class="text-gray-500 text-sm">No time slots available for this date.</p>
            @endif
        </div>
    </div>

    <!-- Step 3: Booking Details -->
    <div class="{{ $step === 3 ? '' : 'hidden' }}">
        <div class="bg-white rounded-xl border p-6 max-w-2xl">
            <div class="mb-6">
                <h3 class="font-semibold text-gray-800">Booking Details</h3>
                <div class="mt-2 p-3 bg-blue-50 rounded-lg text-sm text-blue-800">
                    <strong>{{ $selectedRoom?->name }}</strong> &middot; {{ $booking_date }} &middot; {{ substr($start_time, 0, 5) }} - {{ substr($end_time, 0, 5) }}
                </div>
            </div>

            <form wire:submit="submitBooking" class="space-y-4">
                <!-- Purpose -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Purpose *</label>
                    <input type="text" wire:model="purpose" placeholder="e.g., Team meeting, Training session, Client call"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('purpose') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                </div>

                <!-- Description (optional) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description (optional)</label>
                    <textarea wire:model="description" rows="3" placeholder="Any additional details..."
                              class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>

                <!-- Attendees -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Number of Attendees</label>
                    <input type="number" wire:model="attendees_count" min="1" max="{{ $selectedRoom?->capacity ?? 100 }}"
                           class="w-32 px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @if($selectedRoom)
                        <span class="ml-2 text-sm text-gray-500">(Room capacity: {{ $selectedRoom->capacity }})</span>
                    @endif
                </div>

                <!-- Actions -->
                <div class="flex items-center space-x-3 pt-4">
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition shadow-sm">
                        Confirm Booking
                    </button>
                    <button type="button" wire:click="$set('step', 2)" class="px-6 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition">
                        Back
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
