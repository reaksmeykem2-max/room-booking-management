<?php

namespace App\Livewire\User;

use App\Models\Booking;
use App\Services\BookingService;
use Livewire\Component;
use Livewire\WithPagination;

class MyBookings extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $message = '';
    public $messageType = 'success';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function cancelBooking($bookingId)
    {
        $booking = Booking::findOrFail($bookingId);
        $service = app(BookingService::class);

        $result = $service->cancelBooking($booking, auth()->user());

        $this->message = $result['message'];
        $this->messageType = $result['success'] ? 'success' : 'error';
    }

    public function render()
    {
        $bookings = Booking::where('user_id', auth()->id())
            ->with(['room'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('purpose', 'like', "%{$this->search}%")
                      ->orWhereHas('room', function ($rq) {
                          $rq->where('name', 'like', "%{$this->search}%");
                      });
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->orderByDesc('booking_date')
            ->orderByDesc('start_time')
            ->paginate(10);

        return view('livewire.user.my-bookings', [
            'bookings' => $bookings,
        ]);
    }
}
