<div>
    <!-- Message -->
    @if($message)
        <div class="mb-4 p-4 rounded-lg {{ $messageType === 'success' ? 'bg-green-50 border border-green-200 text-green-700' : 'bg-red-50 border border-red-200 text-red-700' }}">
            {{ $message }}
        </div>
    @endif

    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search rooms..."
               class="px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 w-64">
        <button wire:click="openCreateForm" class="px-4 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
            + Add Room
        </button>
    </div>

    <!-- Rooms Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($rooms as $room)
            <div class="bg-white rounded-xl border p-5 {{ !$room->is_active ? 'opacity-60' : '' }}">
                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="font-semibold text-gray-800">{{ $room->name }}</h3>
                        <p class="text-sm text-gray-500 mt-1">
                            @if($room->location) {{ $room->location }} @endif
                            @if($room->floor) &middot; Floor {{ $room->floor }} @endif
                        </p>
                    </div>
                    <span class="px-2 py-0.5 text-xs rounded-full {{ $room->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                        {{ $room->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>

                <div class="mt-3 space-y-1 text-sm text-gray-600">
                    <p>Capacity: <strong>{{ $room->capacity }}</strong></p>
                    <p>Hours: {{ substr($room->getRawOriginal('available_from'), 0, 5) }} - {{ substr($room->getRawOriginal('available_until'), 0, 5) }}</p>
                    <p>Approval: <span class="{{ $room->requires_approval ? 'text-yellow-600' : 'text-green-600' }}">{{ $room->requires_approval ? 'Required' : 'Auto' }}</span></p>
                </div>

                <div class="mt-4 flex space-x-2">
                    <button wire:click="editRoom({{ $room->id }})" class="px-3 py-1.5 text-xs bg-blue-50 text-blue-700 border border-blue-200 rounded hover:bg-blue-100">
                        Edit
                    </button>
                    <button wire:click="toggleActive({{ $room->id }})" class="px-3 py-1.5 text-xs bg-gray-50 text-gray-600 border border-gray-200 rounded hover:bg-gray-100">
                        {{ $room->is_active ? 'Deactivate' : 'Activate' }}
                    </button>
                    <button wire:click="deleteRoom({{ $room->id }})" wire:confirm="Delete this room? This cannot be undone."
                            class="px-3 py-1.5 text-xs bg-red-50 text-red-600 border border-red-200 rounded hover:bg-red-100">
                        Delete
                    </button>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $rooms->links() }}
    </div>

    <!-- Create/Edit Form Modal -->
    @if($showForm)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 overflow-y-auto" x-data>
            <div class="bg-white rounded-xl p-6 w-full max-w-lg mx-4 my-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    {{ $editingRoomId ? 'Edit Room' : 'Add New Room' }}
                </h3>

                <form wire:submit="saveRoom" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Room Name *</label>
                        <input type="text" wire:model="name" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="e.g., Meeting Room A">
                        @error('name') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                            <input type="text" wire:model="location" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg" placeholder="Building A">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Floor</label>
                            <input type="text" wire:model="floor" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg" placeholder="3">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Capacity *</label>
                        <input type="number" wire:model="capacity" min="1" class="w-32 px-4 py-2.5 border border-gray-300 rounded-lg">
                        @error('capacity') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Available From</label>
                            <input type="time" wire:model="available_from" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Available Until</label>
                            <input type="time" wire:model="available_until" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea wire:model="description" rows="2" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg" placeholder="Room facilities, equipment, etc."></textarea>
                    </div>

                    <div class="flex items-center space-x-6">
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" wire:model="requires_approval" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm text-gray-700">Requires admin approval</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" wire:model="is_active" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm text-gray-700">Active</span>
                        </label>
                    </div>

                    <div class="flex space-x-3 justify-end pt-4 border-t">
                        <button type="button" wire:click="closeForm" class="px-4 py-2 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            {{ $editingRoomId ? 'Update Room' : 'Create Room' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
