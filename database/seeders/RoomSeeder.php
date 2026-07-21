<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $rooms = [
            [
                'name' => 'Meeting Room A',
                'location' => 'Building 1',
                'floor' => '1',
                'capacity' => 8,
                'description' => 'Small meeting room with whiteboard and projector',
                'facilities' => ['projector', 'whiteboard', 'wifi'],
                'requires_approval' => false,
                'available_from' => '08:00:00',
                'available_until' => '17:00:00',
            ],
            [
                'name' => 'Meeting Room B',
                'location' => 'Building 1',
                'floor' => '2',
                'capacity' => 12,
                'description' => 'Medium meeting room with video conferencing',
                'facilities' => ['projector', 'video_conference', 'whiteboard', 'wifi'],
                'requires_approval' => false,
                'available_from' => '08:00:00',
                'available_until' => '17:00:00',
            ],
            [
                'name' => 'Conference Hall',
                'location' => 'Building 1',
                'floor' => '3',
                'capacity' => 50,
                'description' => 'Large conference room for presentations and events',
                'facilities' => ['projector', 'sound_system', 'video_conference', 'stage', 'wifi'],
                'requires_approval' => true,
                'available_from' => '08:00:00',
                'available_until' => '17:00:00',
            ],
            [
                'name' => 'Training Room',
                'location' => 'Building 2',
                'floor' => '1',
                'capacity' => 25,
                'description' => 'Room with individual workstations for training sessions',
                'facilities' => ['projector', 'computers', 'whiteboard', 'wifi'],
                'requires_approval' => true,
                'available_from' => '08:00:00',
                'available_until' => '17:00:00',
            ],
            [
                'name' => 'Boardroom',
                'location' => 'Building 1',
                'floor' => '5',
                'capacity' => 15,
                'description' => 'Executive boardroom with premium facilities',
                'facilities' => ['projector', 'video_conference', 'tv_display', 'wifi', 'phone'],
                'requires_approval' => true,
                'available_from' => '08:00:00',
                'available_until' => '17:00:00',
            ],
        ];

        foreach ($rooms as $room) {
            Room::create($room);
        }
    }
}
