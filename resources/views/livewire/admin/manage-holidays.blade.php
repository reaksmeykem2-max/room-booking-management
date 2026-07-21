<div>
    <!-- Message -->
    @if($message)
        <div class="mb-4 p-4 rounded-lg {{ $messageType === 'success' ? 'bg-green-50 border border-green-200 text-green-700' : 'bg-red-50 border border-red-200 text-red-700' }}">
            {{ $message }}
        </div>
    @endif

    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <p class="text-gray-500">Manage public holidays — bookings are blocked on these dates.</p>
        <button wire:click="openCreateForm" class="px-4 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700">
            + Add Holiday
        </button>
    </div>

    <!-- Holidays List -->
    <div class="bg-white rounded-xl border overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Recurring</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($holidays as $holiday)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm text-gray-800">
                            {{ $holiday->date->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-medium text-gray-800">{{ $holiday->name }}</p>
                            @if($holiday->description)
                                <p class="text-xs text-gray-500">{{ $holiday->description }}</p>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($holiday->is_recurring)
                                <span class="px-2 py-0.5 text-xs bg-blue-100 text-blue-700 rounded-full">Every Year</span>
                            @else
                                <span class="text-xs text-gray-400">One-time</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex space-x-2">
                                <button wire:click="editHoliday({{ $holiday->id }})"
                                        class="px-3 py-1 text-xs bg-blue-50 text-blue-700 border border-blue-200 rounded hover:bg-blue-100">
                                    Edit
                                </button>
                                <button wire:click="deleteHoliday({{ $holiday->id }})"
                                        wire:confirm="Remove this holiday?"
                                        class="px-3 py-1 text-xs bg-red-50 text-red-600 border border-red-200 rounded hover:bg-red-100">
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-400">No holidays configured.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $holidays->links() }}</div>


    <!-- Create/Edit Form Modal -->
    @if($showForm)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-xl p-6 w-full max-w-md mx-4">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    {{ $editingId ? 'Edit Holiday' : 'Add Holiday' }}
                </h3>
                <form wire:submit="saveHoliday" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date *</label>
                        <input type="date" wire:model="date" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        @error('date') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Holiday Name *</label>
                        <input type="text" wire:model="name" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg" placeholder="e.g., New Year">
                        @error('name') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <input type="text" wire:model="description" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg" placeholder="Optional">
                    </div>
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" wire:model="is_recurring" class="rounded border-gray-300 text-blue-600">
                        <span class="text-sm text-gray-700">Repeats every year (same date)</span>
                    </label>
                    <div class="flex space-x-3 justify-end pt-4 border-t">
                        <button type="button" wire:click="closeForm" class="px-4 py-2 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">Cancel</button>
                        <button type="submit" class="px-4 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            {{ $editingId ? 'Update' : 'Add Holiday' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
