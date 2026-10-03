<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Mentor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'tagline',
        'description',
        'expertise',
        'price',
        'whatsapp_number',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'expertise' => 'array',
        'price' => 'integer',
        'is_active' => 'boolean',
    ];

    // ── Slug ──

    public static function boot(): void
    {
        parent::boot();

        static::creating(function (Mentor $mentor) {
            if (empty($mentor->slug)) {
                $mentor->slug = Str::slug($mentor->name);
            }
        });
    }

    // ── Relations ──

    public function images(): HasMany
    {
        return $this->hasMany(MentorImage::class)->orderBy('sort_order');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(MentorBooking::class);
    }

    // ── Accessors ──

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp'.number_format($this->price, 0, ',', '.');
    }

    public function getImageUrlAttribute(): ?string
    {
        $firstImage = $this->images->first();

        if (! $firstImage) {
            return null;
        }

        return Storage::disk('public')->url($firstImage->image_path);
    }

    /**
     * Build a wa.me deep link with a pre-filled message.
     */
    public function whatsappLink(string $message): string
    {
        $number = preg_replace('/\D/', '', $this->whatsapp_number) ?? '';

        if (str_starts_with($number, '0')) {
            $number = '62'.substr($number, 1);
        } elseif (str_starts_with($number, '8')) {
            $number = '62'.$number;
        }

        return 'https://wa.me/'.$number.'?text='.urlencode($message);
    }

    // ── Scopes ──

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }
}
