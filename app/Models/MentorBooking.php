<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class MentorBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'mentor_id',
        'client_name',
        'client_email',
        'client_whatsapp',
        'amount',
        'payment_method',
        'payment_channel',
        'xendit_id',
        'external_id',
        'invoice_url',
        'status',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'integer',
        'paid_at' => 'datetime',
    ];

    // ── Code ──

    public static function boot(): void
    {
        parent::boot();

        static::creating(function (MentorBooking $booking) {
            if (empty($booking->code)) {
                $booking->code = self::generateCode();
            }
        });
    }

    protected static function generateCode(): string
    {
        do {
            $code = 'BIM-'.strtoupper(Str::random(6));
        } while (self::where('code', $code)->exists());

        return $code;
    }

    // ── Relations ──

    public function mentor(): BelongsTo
    {
        return $this->belongsTo(Mentor::class);
    }

    // ── Accessors ──

    public function getFormattedAmountAttribute(): string
    {
        return 'Rp'.number_format($this->amount, 0, ',', '.');
    }

    public function getIsPaidAttribute(): bool
    {
        return $this->isPaid();
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function getExternalIdAttribute(?string $value): string
    {
        return $value ?: 'mentor_booking_'.$this->id;
    }

    // ── Scopes ──

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // ── Status Transitions ──

    public function markAsPaid(?string $xenditId = null): bool
    {
        if ($this->status === 'paid') {
            return true;
        }

        $this->status = 'paid';
        $this->paid_at = now();
        if ($xenditId) {
            $this->xendit_id = $xenditId;
        }

        return $this->save();
    }

    public function markAsFailed(): bool
    {
        $this->status = 'failed';

        return $this->save();
    }

    public function markAsExpired(): bool
    {
        $this->status = 'expired';

        return $this->save();
    }

    public function markAsCancelled(): bool
    {
        $this->status = 'cancelled';

        return $this->save();
    }
}
