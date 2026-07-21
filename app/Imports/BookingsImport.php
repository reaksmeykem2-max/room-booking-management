<?php

namespace App\Imports;

use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BookingsImport implements ToArray, WithHeadingRow
{
    protected $results = [
        'imported' => 0,
        'skipped' => 0,
        'errors' => [],
    ];

    public function array(array $rows): void
    {
        foreach ($rows as $row) {
            try {
                // Try to find room by name
                $room = Room::where('name', 'like', '%' . ($row['room'] ?? $row['room_name'] ?? '') . '%')->first();
                if (!$room) {
                    $this->results['errors'][] = "Room not found: " . ($row['room'] ?? $row['room_name'] ?? 'unknown');
                    $this->results['skipped']++;
                    continue;
                }

                // Try to find user by name or email
                $userName = $row['booked_by'] ?? $row['user'] ?? $row['name'] ?? null;
                $user = User::where('name', 'like', '%' . $userName . '%')
                    ->orWhere('email', 'like', '%' . $userName . '%')
                    ->first();

                if (!$user) {
                    // Create user if not found
                    $user = User::create([
                        'name' => $userName ?? 'Unknown User',
                        'email' => strtolower(str_replace(' ', '.', $userName ?? 'unknown')) . '@company.com',
                        'password' => bcrypt('password'),
                        'role' => 'user',
                    ]);
                }

                // Parse date
                $date = $row['date'] ?? $row['booking_date'] ?? null;
                if (!$date) {
                    $this->results['skipped']++;
                    continue;
                }

                // Parse times
                $startTime = $row['start_time'] ?? $row['from'] ?? '09:00';
                $endTime = $row['end_time'] ?? $row['to'] ?? '10:00';

                Booking::create([
                    'room_id' => $room->id,
                    'user_id' => $user->id,
                    'booking_date' => date('Y-m-d', strtotime($date)),
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'purpose' => $row['purpose'] ?? $row['subject'] ?? 'Imported booking',
                    'status' => 'approved',
                    'attendees_count' => $row['attendees'] ?? 1,
                ]);

                $this->results['imported']++;
            } catch (\Exception $e) {
                $this->results['errors'][] = $e->getMessage();
                $this->results['skipped']++;
            }
        }
    }

    public function getResults(): array
    {
        return $this->results;
    }
}
