<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'field_id',
        'date',
        'start_time',
        'end_time',
        'type',
        'title',
        'description',
        'is_recurring',
        'recurring_type',
        'recurring_end_date',
        'recurring_days',
        'created_by',
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'is_recurring' => 'boolean',
        'recurring_end_date' => 'date',
        'recurring_days' => 'array',
    ];

    /**
     * Get the field this schedule belongs to.
     */
    public function field(): BelongsTo
    {
        return $this->belongsTo(Field::class);
    }

    /**
     * Get the admin who created this schedule.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Check if schedule conflicts with a time range.
     */
    public function conflictsWith($startTime, $endTime): bool
    {
        $scheduleStart = Carbon::parse($this->start_time)->format('H:i');
        $scheduleEnd = Carbon::parse($this->end_time)->format('H:i');

        return $scheduleStart < $endTime && $scheduleEnd > $startTime;
    }

    /**
     * Get schedule type label.
     */
    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'blocked' => 'Diblokir',
            'maintenance' => 'Maintenance',
            'event' => 'Event',
            'private' => 'Private',
            default => 'Unknown',
        };
    }

    /**
     * Get schedule type color.
     */
    public function getTypeColorAttribute(): string
    {
        return match ($this->type) {
            'blocked' => 'danger',
            'maintenance' => 'warning',
            'event' => 'info',
            'private' => 'primary',
            default => 'secondary',
        };
    }

    /**
     * Get formatted time range.
     */
    public function getTimeRangeAttribute(): string
    {
        return Carbon::parse($this->start_time)->format('H:i') . ' - ' .
            Carbon::parse($this->end_time)->format('H:i');
    }

    /**
     * Get formatted date.
     */
    public function getFormattedDateAttribute(): string
    {
        return Carbon::parse($this->date)->format('d M Y');
    }

    /**
     * Generate recurring schedules.
     */
    public function generateRecurringSchedules(): int
    {
        if (!$this->is_recurring || !$this->recurring_end_date) {
            return 0;
        }

        $count = 0;
        $currentDate = Carbon::parse($this->date)->addDay(); // Start from next day
        $endDate = Carbon::parse($this->recurring_end_date);

        while ($currentDate->lte($endDate)) {
            $shouldCreate = false;

            switch ($this->recurring_type) {
                case 'daily':
                    $shouldCreate = true;
                    break;

                case 'weekly':
                    $dayOfWeek = $currentDate->dayOfWeek;
                    $shouldCreate = in_array($dayOfWeek, $this->recurring_days ?? []);
                    break;

                case 'monthly':
                    $shouldCreate = $currentDate->day === Carbon::parse($this->date)->day;
                    break;
            }

            if ($shouldCreate) {
                // Check if schedule doesn't already exist
                $existingSchedule = static::where('field_id', $this->field_id)
                    ->where('date', $currentDate->format('Y-m-d'))
                    ->where('start_time', $this->start_time)
                    ->where('end_time', $this->end_time)
                    ->first();

                if (!$existingSchedule) {
                    static::create([
                        'field_id' => $this->field_id,
                        'date' => $currentDate->format('Y-m-d'),
                        'start_time' => $this->start_time,
                        'end_time' => $this->end_time,
                        'type' => $this->type,
                        'title' => $this->title,
                        'description' => $this->description,
                        'is_recurring' => false, // Individual instances are not recurring
                        'created_by' => $this->created_by,
                    ]);
                    $count++;
                }
            }

            // Move to next interval
            switch ($this->recurring_type) {
                case 'daily':
                    $currentDate->addDay();
                    break;
                case 'weekly':
                    $currentDate->addDay();
                    break;
                case 'monthly':
                    $currentDate->addMonth();
                    break;
            }
        }

        return $count;
    }

    /**
     * Scope for schedules by type.
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope for schedules by date range.
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    /**
     * Scope for today's schedules.
     */
    public function scopeToday($query)
    {
        return $query->where('date', Carbon::today());
    }

    /**
     * Scope for upcoming schedules.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('date', '>=', Carbon::today());
    }

    /**
     * Scope for recurring schedules.
     */
    public function scopeRecurring($query)
    {
        return $query->where('is_recurring', true);
    }

    /**
     * Scope for schedules that conflict with a time range.
     */
    public function scopeConflictingWith($query, $date, $startTime, $endTime)
    {
        return $query->where('date', $date)
            ->where(function ($q) use ($startTime, $endTime) {
                $q->where(function ($subQuery) use ($startTime, $endTime) {
                    $subQuery->where('start_time', '<', $endTime)
                        ->where('end_time', '>', $startTime);
                });
            });
    }
}
