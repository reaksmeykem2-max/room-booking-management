<?php

namespace Database\Seeders;

use App\Models\Holiday;
use Illuminate\Database\Seeder;

class HolidaySeeder extends Seeder
{
    public function run(): void
    {
        $holidays = [
            [
                'date' => date('Y') . '-01-01',
                'name' => 'New Year\'s Day',
                'is_recurring' => true,
            ],
            [
                'date' => date('Y') . '-01-07',
                'name' => 'Victory over Genocide Day',
                'is_recurring' => true,
            ],
            [
                'date' => date('Y') . '-03-08',
                'name' => 'International Women\'s Day',
                'is_recurring' => true,
            ],
            [
                'date' => date('Y') . '-04-14',
                'name' => 'Khmer New Year',
                'is_recurring' => true,
            ],
            [
                'date' => date('Y') . '-04-15',
                'name' => 'Khmer New Year (Day 2)',
                'is_recurring' => true,
            ],
            [
                'date' => date('Y') . '-04-16',
                'name' => 'Khmer New Year (Day 3)',
                'is_recurring' => true,
            ],
            [
                'date' => date('Y') . '-05-01',
                'name' => 'International Labour Day',
                'is_recurring' => true,
            ],
            [
                'date' => date('Y') . '-05-14',
                'name' => 'King\'s Birthday',
                'is_recurring' => true,
            ],
            [
                'date' => date('Y') . '-06-18',
                'name' => 'King\'s Mother Birthday',
                'is_recurring' => true,
            ],
            [
                'date' => date('Y') . '-09-24',
                'name' => 'Constitution Day',
                'is_recurring' => true,
            ],
            [
                'date' => date('Y') . '-10-15',
                'name' => 'Commemoration Day of King Father',
                'is_recurring' => true,
            ],
            [
                'date' => date('Y') . '-10-29',
                'name' => 'King Coronation Day',
                'is_recurring' => true,
            ],
            [
                'date' => date('Y') . '-11-09',
                'name' => 'Independence Day',
                'is_recurring' => true,
            ],
            [
                'date' => date('Y') . '-12-25',
                'name' => 'Christmas Day',
                'is_recurring' => true,
            ],
        ];

        foreach ($holidays as $holiday) {
            Holiday::create($holiday);
        }
    }
}
