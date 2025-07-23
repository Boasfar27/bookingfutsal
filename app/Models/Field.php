<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Field extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'type',
        'location',
        'price_per_hour',
        'images',
        'facilities',
        'open_time',
        'close_time',
        'operating_days',
        'is_active',
        'admin_id',
        'payment_info',
        'max_hours_per_booking',
    ];

    protected $casts = [
        'images' => 'array',
        'facilities' => 'array',
        'operating_days' => 'array',
        'is_active' => 'boolean',
        'price_per_hour' => 'decimal:2',
        'open_time' => 'datetime:H:i',
        'close_time' => 'datetime:H:i',
    ];

    /**
     * Get the admin who owns this field.
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Get the bookings for this field.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get the schedules for this field.
     */
    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    /**
     * Get confirmed bookings only.
     */
    public function confirmedBookings(): HasMany
    {
        return $this->hasMany(Booking::class)->where('status', 'confirmed');
    }

    /**
     * Check if field is available at specific date and time.
     */
    public function isAvailable($date, $startTime, $endTime): bool
    {
        // Check if field is active
        if (!$this->is_active) {
            return false;
        }

        // Check if date is within operating days
        $dayOfWeek = Carbon::parse($date)->dayOfWeek;
        if (!in_array($dayOfWeek, $this->operating_days ?? [1, 2, 3, 4, 5, 6, 7])) {
            return false;
        }

        // Check if time is within operating hours
        $openTime = Carbon::parse($this->open_time)->format('H:i');
        $closeTime = Carbon::parse($this->close_time)->format('H:i');

        if ($startTime < $openTime || $endTime > $closeTime) {
            return false;
        }

        // Check for existing confirmed bookings
        $conflictingBookings = $this->bookings()
            ->where('booking_date', $date)
            ->where('status', 'confirmed')
            ->where(function ($query) use ($startTime, $endTime) {
                $query->where(function ($q) use ($startTime, $endTime) {
                    $q->where('start_time', '<', $endTime)
                        ->where('end_time', '>', $startTime);
                });
            })
            ->exists();

        if ($conflictingBookings) {
            return false;
        }

        // Check for blocked schedules
        $blockedSchedules = $this->schedules()
            ->where('date', $date)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->where(function ($q) use ($startTime, $endTime) {
                    $q->where('start_time', '<', $endTime)
                        ->where('end_time', '>', $startTime);
                });
            })
            ->exists();

        return !$blockedSchedules;
    }

    /**
     * Get available time slots for a specific date.
     */
    public function getAvailableSlots($date): array
    {
        $slots = [];
        $openTime = Carbon::parse($this->open_time);
        $closeTime = Carbon::parse($this->close_time);

        // Generate hourly slots
        $currentTime = $openTime->copy();
        while ($currentTime->lt($closeTime)) {
            $endTime = $currentTime->copy()->addHour();

            if ($this->isAvailable($date, $currentTime->format('H:i:s'), $endTime->format('H:i:s'))) {
                $slots[] = [
                    'start_time' => $currentTime->format('H:i'),
                    'end_time' => $endTime->format('H:i'),
                    'available' => true,
                ];
            } else {
                $slots[] = [
                    'start_time' => $currentTime->format('H:i'),
                    'end_time' => $endTime->format('H:i'),
                    'available' => false,
                ];
            }

            $currentTime->addHour();
        }

        return $slots;
    }

    /**
     * Calculate total price for booking duration.
     */
    public function calculatePrice($hours): float
    {
        return $this->price_per_hour * $hours;
    }

    /**
     * Get the first image URL.
     */
    public function getFirstImageAttribute(): ?string
    {
        $images = $this->images ?? [];
        return count($images) > 0 ? $images[0] : null;
    }

    /**
     * Scope for active fields only.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for fields by type.
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }
}
