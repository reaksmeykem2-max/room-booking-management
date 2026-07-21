<div>
    <!-- Message -->
    @if($message)
        <div class="mb-4 p-4 rounded-lg {{ $messageType === 'success' ? 'bg-green-50 border border-green-200 text-green-700' : 'bg-red-50 border border-red-200 text-red-700' }}">
            {{ $message }}
        </div>
    @endif

    <div class="mb-6">
        <p class="text-gray-500">Configure which days are working days and set office hours.</p>
    </div>

    <div class="bg-white rounded-xl border overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Day</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Working Day</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Start Time</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">End Time</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($schedules as $index => $schedule)
                    <tr class="{{ $schedule['is_working_day'] ? 'bg-white' : 'bg-gray-50' }}">
                        <td class="px-6 py-4">
                            <span class="font-medium text-gray-800">{{ $schedule['day_name'] }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:click="toggleWorkingDay({{ $index }})"
                                       {{ $schedule['is_working_day'] ? 'checked' : '' }}
                                       class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:ring-2 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </td>
                        <td class="px-6 py-4">
                            <input type="time" wire:model="schedules.{{ $index }}.start_time"
                                   class="px-3 py-2 border border-gray-300 rounded-lg text-sm {{ !$schedule['is_working_day'] ? 'opacity-50' : '' }}"
                                   {{ !$schedule['is_working_day'] ? 'disabled' : '' }}>
                        </td>
                        <td class="px-6 py-4">
                            <input type="time" wire:model="schedules.{{ $index }}.end_time"
                                   class="px-3 py-2 border border-gray-300 rounded-lg text-sm {{ !$schedule['is_working_day'] ? 'opacity-50' : '' }}"
                                   {{ !$schedule['is_working_day'] ? 'disabled' : '' }}>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4 flex justify-end">
        <button wire:click="saveSchedules" class="px-6 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition">
            Save Changes
        </button>
    </div>
</div>
