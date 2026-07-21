<div>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Room Availability</h2>
        <p class="text-gray-500 mt-1">See which rooms are free at a glance</p>
    </div>

    <!-- Date Navigator -->
    <div class="mb-6 flex items-center space-x-4 bg-white rounded-xl border p-4">
        <button wire:click="previousDay" class="p-2 rounded-lg hover:bg-gray-100 transition">
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </button>

        <div class="flex-1 flex items-center justify-center space-x-3">
            <input type="date" wire:model.live="selectedDate"
                   class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            <span class="text-lg font-semibold text-gray-800">
                {{ date('l, M d Y', strtotime($selectedDate)) }}
            </span>
        </div>

        <button wire:click="nextDay" class="p-2 rounded-lg hover:bg-gray-100 transition">
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </button>
    </div>

    <!-- Rooms Grid -->
    <div class="space-y-4">
        @foreach($roomSlots as $room)
            <div class="bg-white rounded-xl border p-5">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <h3 class="font-semibold text-gray-800">{{ $room['room_name'] }}</h3>
                        <p class="text-sm text-gray-500">
                            Capacity: {{ $room['capacity'] }}
                            @if($room['location']) &middot; {{ $room['location'] }} @endif
                        </p>
                    </div>
                    @if($room['is_bookable'])
                        <a href="{{ route('book-room') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">Book &rarr;</a>
                    @endif
                </div>

                @if(!$room['is_bookable'])
                    <div class="p-3 bg-yellow-50 rounded-lg">
                        <p class="text-sm text-yellow-700">{{ $room['reason'] }}</p>
                    </div>
                @else
                    <div class="flex flex-wrap gap-2">
                        @foreach($room['slots'] as $slot)
                            <div class="px-3 py-2 rounded-lg text-xs font-medium
                                        {{ $slot['available'] ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-red-50 text-red-600 border border-red-200' }}">
                                {{ $slot['label'] }}
                                @if(!$slot['available'] && $slot['booking'])
                                    <span class="block text-xs opacity-75">{{ $slot['booking']['user']['name'] ?? 'Booked' }}</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach

        @if(count($roomSlots) === 0)
            <div class="text-center py-12 bg-white rounded-xl border">
                <p class="text-gray-500">No rooms available.</p>
            </div>
        @endif
    </div>
</div>
