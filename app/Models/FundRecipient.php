<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FundRecipient extends Model
{
    use HasFactory;

    protected $table = 'fund_recipients';

    protected $fillable = [
        'campaign_id',
        'school_id',
        'purpose',
        'notes',
        'allocated_amount',
        'status',
        'disbursed_at',
    ];

    protected $casts = [
        'allocated_amount' => 'integer',
        'disbursed_at' => 'datetime',
    ];

    // ── Relations ──

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    // ── Accessors ──

    public function getFormattedAmountAttribute(): string
    {
        return 'Rp'.number_format($this->allocated_amount, 0, ',', '.');
    }

    // ── Scopes ──

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeAllocated($query)
    {
        return $query->where('status', 'allocated');
    }

    public function scopeDisbursed($query)
    {
        return $query->where('status', 'disbursed');
    }

    public static function purposes(): array
    {
        return [
            'asrama' => 'Perbaikan Asrama',
            'scholarship' => 'Beasiswa Santri',
            'mosque' => 'Renovasi Masjid',
            'renovation' => 'Renovasi Gedung',
            'facilities' => 'Peningkatan Fasilitas',
            'equipment' => 'Peralatan Belajar',
            'medical' => 'Bantuan Medis',
            'emergency' => 'Bantuan Darurat',
        ];
    }
}
