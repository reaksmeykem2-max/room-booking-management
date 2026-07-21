<?php

namespace App\Livewire\Admin;

use App\Models\Booking;
use App\Services\BookingService;
use Livewire\Component;
use Livewire\WithPagination;

class ManageBookings extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $dateFilter = '';
    public $roomFilter = '';
    public $message = '';
    public $messageType = 'success';
    public $rejectionReason = '';
    public $rejectingBookingId = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function approveBooking($bookingId)
    {
        $booking = Booking::findOrFail($bookingId);
        $service = app(BookingService::class);

        $result = $service->approveBooking($booking, auth()->user());

        $this->message = $result['message'];
        $this->messageType = $result['success'] ? 'success' : 'error';
    }

    public function openRejectModal($bookingId)
    {
        $this->rejectingBookingId = $bookingId;
        $this->rejectionReason = '';
    }

    public function rejectBooking()
    {
        if (!$this->rejectingBookingId) return;

        $booking = Booking::findOrFail($this->rejectingBookingId);
        $service = app(BookingService::class);

        $result = $service->rejectBooking($booking, auth()->user(), $this->rejectionReason);

        $this->message = $result['message'];
        $this->messageType = $result['success'] ? 'success' : 'error';
        $this->rejectingBookingId = null;
        $this->rejectionReason = '';
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
        $bookings = Booking::with(['room', 'user', 'approver'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('purpose', 'like', "%{$this->search}%")
                      ->orWhereHas('user', function ($uq) {
                          $uq->where('name', 'like', "%{$this->search}%");
                      })
                      ->orWhereHas('room', function ($rq) {
                          $rq->where('name', 'like', "%{$this->search}%");
                      });
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->dateFilter, function ($query) {
                $query->where('booking_date', $this->dateFilter);
            })
            ->when($this->roomFilter, function ($query) {
                $query->where('room_id', $this->roomFilter);
            })
            ->orderByDesc('booking_date')
            ->orderByDesc('created_at')
            ->paginate(15);

        $rooms = \App\Models\Room::active()->orderBy('name')->get();

        return view('livewire.admin.manage-bookings', [
            'bookings' => $bookings,
            'rooms' => $rooms,
        ]);
    }
}
