<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicationPayment extends Model
{
    protected $fillable = [
        'application_id', 'provider', 'external_id', 'idempotency_key',
        'invoice_url', 'amount', 'breakdown_json', 'status',
        'payment_method', 'paid_at', 'raw_callback', 'recorded_by_user_id',
    ];

    protected $casts = [
        'amount' => 'integer',
        'breakdown_json' => 'array',
        'raw_callback' => 'array',
        'paid_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (ApplicationPayment $payment) {
            if (empty($payment->idempotency_key)) {
                $payment->idempotency_key = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by_user_id');
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu Pembayaran',
            'paid' => 'Terbayar',
            'expired' => 'Kadaluarsa',
            'failed' => 'Gagal',
            default => $this->status,
        };
    }
}
