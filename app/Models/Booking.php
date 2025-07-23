<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Carbon\Carbon;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_code',
        'user_id',
        'field_id',
        'booking_date',
        'start_time',
        'end_time',
        'duration_hours',
        'total_price',
        'status',
        'notes',
        'admin_notes',
        'customer_name',
        'customer_phone',
        'customer_email',
        'confirmed_at',
        'cancelled_at',
        'confirmed_by',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'total_price' => 'decimal:2',
        'confirmed_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {
            if (empty($booking->booking_code)) {
                $booking->booking_code = $booking->generateBookingCode();
            }
        });
    }

    /**
     * Get the user who made the booking.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the field being booked.
     */
    public function field(): BelongsTo
    {
        return $this->belongsTo(Field::class);
    }

    /**
     * Get the admin who confirmed the booking.
     */
    public function confirmedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'confirmed_by');
    }

    /**
     * Get the payment for this booking.
     */
    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    /**
     * Generate unique booking code.
     */
    public function generateBookingCode(): string
    {
        $prefix = 'FSL';
        $date = Carbon::now()->format('Ymd');
        $random = strtoupper(substr(uniqid(), -4));

        return $prefix . $date . $random;
    }

    /**
     * Check if booking can be cancelled.
     */
    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending', 'confirmed']) &&
            Carbon::parse($this->booking_date . ' ' . $this->start_time)->isFuture();
    }

    /**
     * Check if booking can be confirmed.
     */
    public function canBeConfirmed(): bool
    {
        return $this->status === 'pending' &&
            Carbon::parse($this->booking_date . ' ' . $this->start_time)->isFuture();
    }

    /**
     * Confirm the booking.
     */
    public function confirm($adminId = null): bool
    {
        if (!$this->canBeConfirmed()) {
            return false;
        }

        $this->update([
            'status' => 'confirmed',
            'confirmed_at' => now(),
            'confirmed_by' => $adminId,
        ]);

        return true;
    }

    /**
     * Cancel the booking.
     */
    public function cancel(): bool
    {
        if (!$this->canBeCancelled()) {
            return false;
        }

        $this->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);

        return true;
    }

    /**
     * Mark booking as completed.
     */
    public function complete(): bool
    {
        if ($this->status !== 'confirmed') {
            return false;
        }

        // Check if booking time has passed
        $bookingEnd = Carbon::parse($this->booking_date . ' ' . $this->end_time);
        if ($bookingEnd->isFuture()) {
            return false;
        }

        $this->update(['status' => 'completed']);
        return true;
    }

    /**
     * Get booking status badge color.
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'warning',
            'confirmed' => 'success',
            'cancelled' => 'danger',
            'completed' => 'primary',
            default => 'secondary',
        };
    }

    /**
     * Get booking status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu Konfirmasi',
            'confirmed' => 'Dikonfirmasi',
            'cancelled' => 'Dibatalkan',
            'completed' => 'Selesai',
            default => 'Unknown',
        };
    }

    /**
     * Get formatted booking time.
     */
    public function getBookingTimeAttribute(): string
    {
        return Carbon::parse($this->start_time)->format('H:i') . ' - ' .
            Carbon::parse($this->end_time)->format('H:i');
    }

    /**
     * Get formatted booking date.
     */
    public function getBookingDateFormattedAttribute(): string
    {
        return Carbon::parse($this->booking_date)->format('d M Y');
    }

    /**
     * Scope for bookings by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for bookings by date range.
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('booking_date', [$startDate, $endDate]);
    }

    /**
     * Scope for today's bookings.
     */
    public function scopeToday($query)
    {
        return $query->where('booking_date', Carbon::today());
    }

    /**
     * Scope for upcoming bookings.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('booking_date', '>=', Carbon::today());
    }
}
