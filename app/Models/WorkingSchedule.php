<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkingSchedule extends Model
{
    protected $fillable = [
        'day_of_week',
        'day_name',
        'start_time',
        'end_time',
        'is_working_day',
    ];

    protected function casts(): array
    {
        return [
            'is_working_day' => 'boolean',
        ];
    }

    // Get working schedule for a specific day (1=Monday, 7=Sunday)
    public static function forDay(int $dayOfWeek): ?self
    {
        return self::where('day_of_week', $dayOfWeek)->first();
    }

    // Check if a given date is a working day
    public static function isWorkingDay(string $date): bool
    {
        $dayOfWeek = date('N', strtotime($date)); // 1=Monday, 7=Sunday
        $schedule = self::forDay($dayOfWeek);

        return $schedule ? $schedule->is_working_day : false;
    }

    // Get working hours for a given date
    public static function getWorkingHours(string $date): ?array
    {
        $dayOfWeek = date('N', strtotime($date));
        $schedule = self::forDay($dayOfWeek);

        if (!$schedule || !$schedule->is_working_day) {
            return null;
        }

        return [
            'start' => $schedule->start_time,
            'end' => $schedule->end_time,
        ];
    }
}
