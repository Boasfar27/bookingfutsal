<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'amount',
        'payment_method',
        'proof_file_path',
        'proof_file_name',
        'status',
        'payment_notes',
        'verification_notes',
        'paid_at',
        'verified_at',
        'rejected_at',
        'verified_by',
        'bank_name',
        'account_holder_name',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'verified_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    /**
     * Get the booking this payment belongs to.
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get the admin who verified this payment.
     */
    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Check if payment can be verified.
     */
    public function canBeVerified(): bool
    {
        return $this->status === 'pending' && !empty($this->proof_file_path);
    }

    /**
     * Check if payment can be rejected.
     */
    public function canBeRejected(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Verify the payment.
     */
    public function verify($adminId, $notes = null): bool
    {
        if (!$this->canBeVerified()) {
            return false;
        }

        $this->update([
            'status' => 'verified',
            'verified_at' => now(),
            'verified_by' => $adminId,
            'verification_notes' => $notes,
        ]);

        // Auto-confirm the related booking
        $this->booking->confirm($adminId);

        return true;
    }

    /**
     * Reject the payment.
     */
    public function reject($adminId, $notes = null): bool
    {
        if (!$this->canBeRejected()) {
            return false;
        }

        $this->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'verified_by' => $adminId,
            'verification_notes' => $notes,
        ]);

        return true;
    }

    /**
     * Get payment status badge color.
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'warning',
            'verified' => 'success',
            'rejected' => 'danger',
            default => 'secondary',
        };
    }

    /**
     * Get payment status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu Verifikasi',
            'verified' => 'Terverifikasi',
            'rejected' => 'Ditolak',
            default => 'Unknown',
        };
    }

    /**
     * Get payment method label.
     */
    public function getPaymentMethodLabelAttribute(): string
    {
        return match ($this->payment_method) {
            'bank_transfer' => 'Transfer Bank',
            'cash' => 'Tunai',
            'ewallet' => 'E-Wallet',
            default => 'Unknown',
        };
    }

    /**
     * Get formatted amount.
     */
    public function getFormattedAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    /**
     * Get proof file URL.
     */
    public function getProofFileUrlAttribute(): ?string
    {
        if (!$this->proof_file_path) {
            return null;
        }

        return asset('storage/' . $this->proof_file_path);
    }

    /**
     * Check if proof file is an image.
     */
    public function isImageProof(): bool
    {
        if (!$this->proof_file_path) {
            return false;
        }

        $extension = strtolower(pathinfo($this->proof_file_path, PATHINFO_EXTENSION));
        return in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
    }

    /**
     * Check if proof file is a PDF.
     */
    public function isPdfProof(): bool
    {
        if (!$this->proof_file_path) {
            return false;
        }

        $extension = strtolower(pathinfo($this->proof_file_path, PATHINFO_EXTENSION));
        return $extension === 'pdf';
    }

    /**
     * Scope for payments by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for pending payments.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for verified payments.
     */
    public function scopeVerified($query)
    {
        return $query->where('status', 'verified');
    }

    /**
     * Scope for rejected payments.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}
