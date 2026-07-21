<?php

namespace App\Livewire\Admin;

use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $stats = [
            'total_rooms' => Room::active()->count(),
            'total_users' => User::where('role', 'user')->count(),
            'bookings_today' => Booking::today()->whereIn('status', ['approved', 'pending'])->count(),
            'pending_approvals' => Booking::pending()->count(),
            'bookings_this_week' => Booking::whereBetween('booking_date', [
                now()->startOfWeek(),
                now()->endOfWeek(),
            ])->whereIn('status', ['approved', 'pending'])->count(),
            'bookings_this_month' => Booking::whereMonth('booking_date', now()->month)
                ->whereYear('booking_date', now()->year)
                ->whereIn('status', ['approved', 'pending'])
                ->count(),
        ];

        $todayBookings = Booking::today()
            ->whereIn('status', ['approved', 'pending'])
            ->with(['room', 'user'])
            ->orderBy('start_time')
            ->get();

        $pendingBookings = Booking::pending()
            ->with(['room', 'user'])
            ->orderBy('booking_date')
            ->orderBy('start_time')
            ->limit(10)
            ->get();

        return view('livewire.admin.dashboard', [
            'stats' => $stats,
            'todayBookings' => $todayBookings,
            'pendingBookings' => $pendingBookings,
        ]);
    }
}
