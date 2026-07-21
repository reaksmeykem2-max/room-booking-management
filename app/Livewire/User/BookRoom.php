<?php

namespace App\Livewire\User;

use App\Models\Room;
use App\Services\BookingService;
use Livewire\Component;

class BookRoom extends Component
{
    // Form fields
    public $room_id = '';
    public $booking_date = '';
    public $start_time = '';
    public $end_time = '';
    public $purpose = '';
    public $description = '';
    public $attendees_count = 1;

    // UI state
    public $timeSlots = [];
    public $suggestions = [];
    public $successMessage = '';
    public $errorMessage = '';
    public $selectedRoom = null;
    public $step = 1; // 1=select room, 2=pick time, 3=fill details

    protected $rules = [
        'room_id' => 'required|exists:rooms,id',
        'booking_date' => 'required|date|after_or_equal:today',
        'start_time' => 'required',
        'end_time' => 'required',
        'purpose' => 'required|min:3|max:255',
        'attendees_count' => 'required|integer|min:1',
    ];

    protected $messages = [
        'room_id.required' => 'Please select a room.',
        'booking_date.required' => 'Please select a date.',
        'booking_date.after_or_equal' => 'You cannot book in the past.',
        'start_time.required' => 'Please select a start time.',
        'end_time.required' => 'Please select an end time.',
        'purpose.required' => 'Please provide a purpose for the booking.',
    ];

    public function updatedRoomId($value)
    {
        if ($value) {
            $this->selectedRoom = Room::find($value);
            $this->step = 2;
            $this->resetTimeSlots();
        }
    }

    public function updatedBookingDate($value)
    {
        if ($value && $this->room_id) {
            $this->loadTimeSlots();
        }
    }

    public function loadTimeSlots()
    {
        $service = app(BookingService::class);

        // Check if date is bookable
        $dateCheck = $service->isDateBookable($this->booking_date);
        if (!$dateCheck['bookable']) {
            $this->errorMessage = $dateCheck['reason'];
            $this->timeSlots = [];
            return;
        }

        $this->errorMessage = '';
        $room = Room::find($this->room_id);
        $this->timeSlots = $service->getTimeSlotsForRoom($room, $this->booking_date);
    }

    public function selectTimeSlot($startTime, $endTime)
    {
        $this->start_time = $startTime;
        $this->end_time = $endTime;
        $this->step = 3;
    }

    public function submitBooking()
    {
        $this->validate();

        $this->errorMessage = '';
        $this->successMessage = '';
        $this->suggestions = [];

        $service = app(BookingService::class);

        $result = $service->createBooking([
            'room_id' => $this->room_id,
            'booking_date' => $this->booking_date,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'purpose' => $this->purpose,
            'description' => $this->description,
            'attendees_count' => $this->attendees_count,
        ], auth()->user());

        if ($result['success']) {
            $this->successMessage = $result['message'];
            $this->resetForm();
        } else {
            $this->errorMessage = $result['message'];
            if (isset($result['suggestions'])) {
                $this->suggestions = $result['suggestions'];
            }
        }
    }

    public function useSuggestion($startTime, $endTime)
    {
        $this->start_time = $startTime;
        $this->end_time = $endTime;
        $this->suggestions = [];
        $this->errorMessage = '';
    }

    public function resetForm()
    {
        $this->room_id = '';
        $this->booking_date = '';
        $this->start_time = '';
        $this->end_time = '';
        $this->purpose = '';
        $this->description = '';
        $this->attendees_count = 1;
        $this->timeSlots = [];
        $this->suggestions = [];
        $this->selectedRoom = null;
        $this->step = 1;
    }

    private function resetTimeSlots()
    {
        $this->timeSlots = [];
        $this->booking_date = '';
        $this->start_time = '';
        $this->end_time = '';
    }

    public function render()
    {
        return view('livewire.user.book-room', [
            'rooms' => Room::active()->orderBy('name')->get(),
        ]);
    }
}
