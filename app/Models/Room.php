<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
        'floor',
        'capacity',
        'description',
        'facilities',
        'requires_approval',
        'available_from',
        'available_until',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'facilities' => 'array',
            'requires_approval' => 'boolean',
            'is_active' => 'boolean',
            'available_from' => 'datetime:H:i',
            'available_until' => 'datetime:H:i',
        ];
    }

    // Relationships
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Helpers
    public function getBookingsForDate(string $date)
    {
        return $this->bookings()
            ->where('booking_date', $date)
            ->whereIn('status', ['approved', 'pending'])
            ->orderBy('start_time')
            ->get();
    }

    public function isAvailableAt(string $date, string $startTime, string $endTime): bool
    {
        return !$this->bookings()
            ->where('booking_date', $date)
            ->whereIn('status', ['approved', 'pending'])
            ->where(function ($query) use ($startTime, $endTime) {
                $query->where(function ($q) use ($startTime, $endTime) {
                    $q->where('start_time', '<', $endTime)
                      ->where('end_time', '>', $startTime);
                });
            })
            ->exists();
    }
}
