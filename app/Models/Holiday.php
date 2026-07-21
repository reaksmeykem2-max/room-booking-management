<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    protected $fillable = [
        'date',
        'name',
        'description',
        'is_recurring',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'is_recurring' => 'boolean',
        ];
    }

    // Check if a given date is a holiday
    public static function isHoliday(string $date): bool
    {
        $checkDate = date('Y-m-d', strtotime($date));

        // Check exact date
        $exists = self::where('date', $checkDate)->exists();
        if ($exists) return true;

        // Check recurring holidays (same month and day, any year)
        $monthDay = date('m-d', strtotime($date));
        return self::where('is_recurring', true)
            ->whereRaw("DATE_FORMAT(date, '%m-%d') = ?", [$monthDay])
            ->exists();
    }

    // Get holiday name for a date (if it is a holiday)
    public static function getHolidayName(string $date): ?string
    {
        $checkDate = date('Y-m-d', strtotime($date));

        $holiday = self::where('date', $checkDate)->first();
        if ($holiday) return $holiday->name;

        $monthDay = date('m-d', strtotime($date));
        $holiday = self::where('is_recurring', true)
            ->whereRaw("DATE_FORMAT(date, '%m-%d') = ?", [$monthDay])
            ->first();

        return $holiday?->name;
    }
}
