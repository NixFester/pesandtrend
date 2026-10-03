<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id',
        'user_id',
        'donor_name',
        'donor_email',
        'donor_phone',
        'donor_message',
        'amount',
        'payment_method',
        'payment_channel',
        'xendit_id',
        'xendit_status',
        'status',
        'paid_at',
        'invoice_url',
    ];

    protected $casts = [
        'amount' => 'integer',
        'paid_at' => 'datetime',
    ];

    // ── Relations ──

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ── Accessors ──

    public function getFormattedAmountAttribute(): string
    {
        return 'Rp'.number_format($this->amount, 0, ',', '.');
    }

    public function getIsPaidAttribute(): bool
    {
        return $this->status === 'paid';
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

        // Update campaign current amount
        $this->campaign->increment('current_amount', $this->amount);

        return $this->save();
    }

    public function markAsFailed(): bool
    {
        $this->status = 'failed';

        return $this->save();
    }

    public function markAsRefunded(): bool
    {
        $this->status = 'refunded';

        // Decrement campaign current amount
        $this->campaign->decrement('current_amount', $this->amount);

        return $this->save();
    }
}
