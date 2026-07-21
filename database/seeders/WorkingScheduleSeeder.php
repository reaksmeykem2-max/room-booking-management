<?php

namespace Database\Seeders;

use App\Models\WorkingSchedule;
use Illuminate\Database\Seeder;

class WorkingScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $days = [
            ['day_of_week' => 1, 'day_name' => 'Monday', 'is_working_day' => true],
            ['day_of_week' => 2, 'day_name' => 'Tuesday', 'is_working_day' => true],
            ['day_of_week' => 3, 'day_name' => 'Wednesday', 'is_working_day' => true],
            ['day_of_week' => 4, 'day_name' => 'Thursday', 'is_working_day' => true],
            ['day_of_week' => 5, 'day_name' => 'Friday', 'is_working_day' => true],
            ['day_of_week' => 6, 'day_name' => 'Saturday', 'is_working_day' => false],
            ['day_of_week' => 7, 'day_name' => 'Sunday', 'is_working_day' => false],
        ];

        foreach ($days as $day) {
            WorkingSchedule::create([
                'day_of_week' => $day['day_of_week'],
                'day_name' => $day['day_name'],
                'start_time' => '08:00:00',
                'end_time' => '17:00:00',
                'is_working_day' => $day['is_working_day'],
            ]);
        }
    }
}
