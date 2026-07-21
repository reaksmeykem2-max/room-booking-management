<?php

namespace App\Livewire\User;

use App\Models\Room;
use App\Services\BookingService;
use Livewire\Component;

class AvailabilityBoard extends Component
{
    public $selectedDate;
    public $roomSlots = [];

    public function mount()
    {
        $this->selectedDate = today()->format('Y-m-d');
        $this->loadAvailability();
    }

    public function updatedSelectedDate()
    {
        $this->loadAvailability();
    }

    public function previousDay()
    {
        $this->selectedDate = date('Y-m-d', strtotime($this->selectedDate . ' -1 day'));
        $this->loadAvailability();
    }

    public function nextDay()
    {
        $this->selectedDate = date('Y-m-d', strtotime($this->selectedDate . ' +1 day'));
        $this->loadAvailability();
    }

    public function loadAvailability()
    {
        $service = app(BookingService::class);
        $rooms = Room::active()->orderBy('name')->get();

        $this->roomSlots = [];

        foreach ($rooms as $room) {
            $dateCheck = $service->isDateBookable($this->selectedDate);

            $this->roomSlots[] = [
                'room_id' => $room->id,
                'room_name' => $room->name,
                'capacity' => $room->capacity,
                'location' => $room->location,
                'is_bookable' => $dateCheck['bookable'],
                'reason' => $dateCheck['reason'] ?? null,
                'slots' => $dateCheck['bookable']
                    ? $service->getTimeSlotsForRoom($room, $this->selectedDate)
                    : [],
            ];
        }
    }

    public function render()
    {
        return view('livewire.user.availability-board');
    }
}
