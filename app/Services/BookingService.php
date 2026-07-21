<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Holiday;
use App\Models\Room;
use App\Models\User;
use App\Models\WorkingSchedule;
use Illuminate\Support\Collection;

class BookingService
{
    /**
     * Attempt to create a booking with all validation checks.
     * Auto-approves if room doesn't require approval.
     */
    public function createBooking(array $data, User $user): array
    {
        $room = Room::findOrFail($data['room_id']);
        $date = $data['booking_date'];
        $startTime = $data['start_time'];
        $endTime = $data['end_time'];

        // 1. Check if it's a working day
        if (!WorkingSchedule::isWorkingDay($date)) {
            $dayName = date('l', strtotime($date));
            return [
                'success' => false,
                'message' => "{$dayName} is not a working day. Please select a working day.",
            ];
        }

        // 2. Check if it's a holiday
        if (Holiday::isHoliday($date)) {
            $holidayName = Holiday::getHolidayName($date);
            return [
                'success' => false,
                'message' => "This date is a holiday ({$holidayName}). Please select another date.",
            ];
        }

        // 3. Check within working hours
        $workingHours = WorkingSchedule::getWorkingHours($date);
        if ($workingHours) {
            if ($startTime < $workingHours['start'] || $endTime > $workingHours['end']) {
                return [
                    'success' => false,
                    'message' => "Booking must be within working hours ({$workingHours['start']} - {$workingHours['end']}).",
                ];
            }
        }

        // 4. Check room availability hours
        if ($startTime < $room->available_from || $endTime > $room->available_until) {
            return [
                'success' => false,
                'message' => "This room is only available from {$room->available_from} to {$room->available_until}.",
            ];
        }

        // 5. Check time logic
        if ($startTime >= $endTime) {
            return [
                'success' => false,
                'message' => "End time must be after start time.",
            ];
        }

        // 6. Check for conflicts (the most important check!)
        if (!$room->isAvailableAt($date, $startTime, $endTime)) {
            $suggestions = $this->suggestAlternativeSlots($room, $date, $startTime, $endTime);
            return [
                'success' => false,
                'message' => "This room is already booked at that time.",
                'suggestions' => $suggestions,
            ];
        }

        // 7. Check attendees vs capacity
        if (isset($data['attendees_count']) && $data['attendees_count'] > $room->capacity) {
            return [
                'success' => false,
                'message' => "This room only fits {$room->capacity} people. You need space for {$data['attendees_count']}.",
            ];
        }

        // All checks passed! Create the booking
        $status = $room->requires_approval ? 'pending' : 'approved';

        $booking = Booking::create([
            'room_id' => $data['room_id'],
            'user_id' => $user->id,
            'booking_date' => $date,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'purpose' => $data['purpose'],
            'description' => $data['description'] ?? null,
            'attendees_count' => $data['attendees_count'] ?? 1,
            'status' => $status,
            'approved_by' => $status === 'approved' ? null : null,
            'approved_at' => $status === 'approved' ? now() : null,
        ]);

        $message = $status === 'approved'
            ? 'Booking confirmed! Your room is reserved.'
            : 'Booking submitted! Waiting for admin approval.';

        return [
            'success' => true,
            'message' => $message,
            'booking' => $booking,
            'status' => $status,
        ];
    }

    /**
     * Suggest alternative time slots when the requested slot is busy.
     */
    public function suggestAlternativeSlots(Room $room, string $date, string $startTime, string $endTime): array
    {
        $duration = (strtotime($endTime) - strtotime($startTime)) / 3600; // hours
        $suggestions = [];

        $workingHours = WorkingSchedule::getWorkingHours($date);
        if (!$workingHours) return [];

        $currentSlotStart = $workingHours['start'];
        $workEnd = $workingHours['end'];

        // Find all free slots of the same duration
        while ($currentSlotStart < $workEnd) {
            $slotEnd = date('H:i:s', strtotime($currentSlotStart) + ($duration * 3600));

            if ($slotEnd > $workEnd) break;

            if ($room->isAvailableAt($date, $currentSlotStart, $slotEnd)) {
                $suggestions[] = [
                    'start_time' => $currentSlotStart,
                    'end_time' => $slotEnd,
                    'label' => substr($currentSlotStart, 0, 5) . ' - ' . substr($slotEnd, 0, 5),
                ];
            }

            // Move to next 30-min slot
            $currentSlotStart = date('H:i:s', strtotime($currentSlotStart) + 1800);
        }

        return array_slice($suggestions, 0, 5); // Return max 5 suggestions
    }

    /**
     * Get available rooms for a given date and time range.
     */
    public function getAvailableRooms(string $date, string $startTime, string $endTime): Collection
    {
        $rooms = Room::active()->get();

        return $rooms->filter(function ($room) use ($date, $startTime, $endTime) {
            return $room->isAvailableAt($date, $startTime, $endTime);
        });
    }

    /**
     * Get time slots for a room on a given date (showing busy/free status).
     */
    public function getTimeSlotsForRoom(Room $room, string $date): array
    {
        $workingHours = WorkingSchedule::getWorkingHours($date);
        if (!$workingHours) return [];

        $slots = [];
        $currentTime = $workingHours['start'];
        $endTime = $workingHours['end'];

        while ($currentTime < $endTime) {
            $slotEnd = date('H:i:s', strtotime($currentTime) + 3600); // 1-hour slots

            if ($slotEnd > $endTime) break;

            $isAvailable = $room->isAvailableAt($date, $currentTime, $slotEnd);

            // Find booking in this slot if busy
            $existingBooking = null;
            if (!$isAvailable) {
                $existingBooking = $room->bookings()
                    ->where('booking_date', $date)
                    ->whereIn('status', ['approved', 'pending'])
                    ->where('start_time', '<', $slotEnd)
                    ->where('end_time', '>', $currentTime)
                    ->with('user')
                    ->first();
            }

            $slots[] = [
                'start_time' => $currentTime,
                'end_time' => $slotEnd,
                'label' => substr($currentTime, 0, 5) . ' - ' . substr($slotEnd, 0, 5),
                'available' => $isAvailable,
                'booking' => $existingBooking,
            ];

            $currentTime = $slotEnd;
        }

        return $slots;
    }

    /**
     * Cancel a booking.
     */
    public function cancelBooking(Booking $booking, User $user): array
    {
        if (!$booking->isCancellable()) {
            return [
                'success' => false,
                'message' => 'This booking cannot be cancelled.',
            ];
        }

        // Users can only cancel their own bookings, admins can cancel any
        if (!$user->isAdmin() && $booking->user_id !== $user->id) {
            return [
                'success' => false,
                'message' => 'You can only cancel your own bookings.',
            ];
        }

        $booking->update(['status' => 'cancelled']);

        return [
            'success' => true,
            'message' => 'Booking has been cancelled.',
        ];
    }

    /**
     * Approve a pending booking (admin only).
     */
    public function approveBooking(Booking $booking, User $admin): array
    {
        if (!$booking->isPending()) {
            return [
                'success' => false,
                'message' => 'Only pending bookings can be approved.',
            ];
        }

        // Double-check availability before approving
        if (!$booking->room->isAvailableAt(
            $booking->booking_date->format('Y-m-d'),
            $booking->start_time,
            $booking->end_time
        )) {
            return [
                'success' => false,
                'message' => 'Cannot approve — another booking now conflicts with this time slot.',
            ];
        }

        $booking->update([
            'status' => 'approved',
            'approved_by' => $admin->id,
            'approved_at' => now(),
        ]);

        return [
            'success' => true,
            'message' => 'Booking approved successfully.',
        ];
    }

    /**
     * Reject a pending booking (admin only).
     */
    public function rejectBooking(Booking $booking, User $admin, string $reason = ''): array
    {
        if (!$booking->isPending()) {
            return [
                'success' => false,
                'message' => 'Only pending bookings can be rejected.',
            ];
        }

        $booking->update([
            'status' => 'rejected',
            'approved_by' => $admin->id,
            'rejection_reason' => $reason,
        ]);

        return [
            'success' => true,
            'message' => 'Booking rejected.',
        ];
    }

    /**
     * Check if a date is bookable (working day + not holiday).
     */
    public function isDateBookable(string $date): array
    {
        if (!WorkingSchedule::isWorkingDay($date)) {
            return ['bookable' => false, 'reason' => 'Not a working day'];
        }

        if (Holiday::isHoliday($date)) {
            $name = Holiday::getHolidayName($date);
            return ['bookable' => false, 'reason' => "Holiday: {$name}"];
        }

        return ['bookable' => true, 'reason' => null];
    }
}
