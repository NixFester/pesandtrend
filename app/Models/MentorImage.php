<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class MentorImage extends Model
{
    protected $fillable = [
        'mentor_id',
        'image_path',
        'caption',
        'type',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    // ── Relations ──

    public function mentor(): BelongsTo
    {
        return $this->belongsTo(Mentor::class);
    }

    // ── Accessors ──

    public function getImageUrlAttribute(): string
    {
        return Storage::disk('public')->url($this->image_path);
    }

    // ── Helpers ──

    public static function types(): array
    {
        return [
            'certificate' => 'Sertifikat',
            'portfolio' => 'Portofolio',
            'example' => 'Contoh Bimbel',
            'other' => 'Lainnya',
        ];
    }
}
