<?php

namespace App\Livewire\Admin;

use App\Models\Room;
use Livewire\Component;
use Livewire\WithPagination;

class ManageRooms extends Component
{
    use WithPagination;

    // Form fields
    public $name = '';
    public $location = '';
    public $floor = '';
    public $capacity = 10;
    public $description = '';
    public $facilities = [];
    public $requires_approval = false;
    public $available_from = '08:00';
    public $available_until = '17:00';
    public $is_active = true;

    // UI state
    public $showForm = false;
    public $editingRoomId = null;
    public $message = '';
    public $messageType = 'success';
    public $search = '';

    protected $rules = [
        'name' => 'required|min:2|max:100',
        'capacity' => 'required|integer|min:1|max:500',
        'available_from' => 'required',
        'available_until' => 'required',
    ];

    public function openCreateForm()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function editRoom($roomId)
    {
        $room = Room::findOrFail($roomId);
        $this->editingRoomId = $roomId;
        $this->name = $room->name;
        $this->location = $room->location ?? '';
        $this->floor = $room->floor ?? '';
        $this->capacity = $room->capacity;
        $this->description = $room->description ?? '';
        $this->facilities = $room->facilities ?? [];
        $this->requires_approval = $room->requires_approval;
        $this->available_from = $room->getRawOriginal('available_from') ? substr($room->getRawOriginal('available_from'), 0, 5) : '08:00';
        $this->available_until = $room->getRawOriginal('available_until') ? substr($room->getRawOriginal('available_until'), 0, 5) : '17:00';
        $this->is_active = $room->is_active;
        $this->showForm = true;
    }

    public function saveRoom()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'location' => $this->location ?: null,
            'floor' => $this->floor ?: null,
            'capacity' => $this->capacity,
            'description' => $this->description ?: null,
            'facilities' => $this->facilities ?: null,
            'requires_approval' => $this->requires_approval,
            'available_from' => $this->available_from . ':00',
            'available_until' => $this->available_until . ':00',
            'is_active' => $this->is_active,
        ];

        if ($this->editingRoomId) {
            Room::findOrFail($this->editingRoomId)->update($data);
            $this->message = 'Room updated successfully!';
        } else {
            Room::create($data);
            $this->message = 'Room created successfully!';
        }

        $this->messageType = 'success';
        $this->showForm = false;
        $this->resetForm();
    }

    public function toggleActive($roomId)
    {
        $room = Room::findOrFail($roomId);
        $room->update(['is_active' => !$room->is_active]);
        $this->message = $room->is_active ? 'Room activated.' : 'Room deactivated.';
        $this->messageType = 'success';
    }

    public function deleteRoom($roomId)
    {
        $room = Room::findOrFail($roomId);

        // Check if room has future bookings
        $futureBookings = $room->bookings()
            ->where('booking_date', '>=', today())
            ->whereIn('status', ['approved', 'pending'])
            ->count();

        if ($futureBookings > 0) {
            $this->message = "Cannot delete: this room has {$futureBookings} upcoming booking(s). Cancel them first.";
            $this->messageType = 'error';
            return;
        }

        $room->delete();
        $this->message = 'Room deleted.';
        $this->messageType = 'success';
    }

    public function resetForm()
    {
        $this->editingRoomId = null;
        $this->name = '';
        $this->location = '';
        $this->floor = '';
        $this->capacity = 10;
        $this->description = '';
        $this->facilities = [];
        $this->requires_approval = false;
        $this->available_from = '08:00';
        $this->available_until = '17:00';
        $this->is_active = true;
    }

    public function closeForm()
    {
        $this->showForm = false;
        $this->resetForm();
    }

    public function render()
    {
        $rooms = Room::when($this->search, function ($query) {
                $query->where('name', 'like', "%{$this->search}%")
                      ->orWhere('location', 'like', "%{$this->search}%");
            })
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.admin.manage-rooms', [
            'rooms' => $rooms,
        ]);
    }
}
