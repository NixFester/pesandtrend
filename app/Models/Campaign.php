<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'title',
        'slug',
        'description',
        'category',
        'target_amount',
        'current_amount',
        'start_date',
        'end_date',
        'status',
        'image',
        'is_featured',
    ];

    protected $casts = [
        'target_amount' => 'integer',
        'current_amount' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_featured' => 'boolean',
    ];

    // ── Slug ──

    public static function boot(): void
    {
        parent::boot();

        static::creating(function (Campaign $campaign) {
            if (empty($campaign->slug)) {
                $campaign->slug = Str::slug($campaign->title);
            }
        });
    }

    // ── Relations ──

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class);
    }

    public function fundRecipients(): HasMany
    {
        return $this->hasMany(FundRecipient::class);
    }

    // ── Accessors ──

    public function getProgressPercentageAttribute(): float
    {
        if ($this->target_amount <= 0) {
            return 0;
        }

        return min(100, round(($this->current_amount / $this->target_amount) * 100, 1));
    }

    public function getRemainingAmountAttribute(): int
    {
        return max(0, $this->target_amount - $this->current_amount);
    }

    public function getFormattedTargetAttribute(): string
    {
        return 'Rp'.number_format($this->target_amount, 0, ',', '.');
    }

    public function getFormattedCurrentAttribute(): string
    {
        return 'Rp'.number_format($this->current_amount, 0, ',', '.');
    }

    public function getFormattedRemainingAttribute(): string
    {
        return 'Rp'.number_format($this->remaining_amount, 0, ',', '.');
    }

    public function getImageUrlAttribute(): string
    {
        if (empty($this->image)) {
            return asset('images/campaigns/default.jpg');
        }

        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }

        if (str_starts_with($this->image, 'images/')) {
            return asset($this->image);
        }

        if (str_starts_with($this->image, 'storage/')) {
            return asset($this->image);
        }

        // For storage paths (e.g., "campaigns/xxx.jpg")
        return Storage::disk('public')->url($this->image);
    }

    /**
     * Download and save image from URL to storage.
     */
    public static function downloadAndSaveImage(string $url): ?string
    {
        if (empty($url) || ! filter_var($url, FILTER_VALIDATE_URL)) {
            return null;
        }

        try {
            $contents = file_get_contents($url);
            if ($contents === false) {
                return null;
            }

            $filename = 'campaigns/'.Str::uuid().'.jpg';
            Storage::disk('public')->put($filename, $contents);

            return $filename;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Mutator to automatically download and save images from URLs.
     */
    public function setImageAttribute(?string $value): void
    {
        if (empty($value)) {
            $this->attributes['image'] = null;

            return;
        }

        // If it's already a local/storage path (from FileUpload), keep it
        if (str_starts_with($value, 'images/') || str_starts_with($value, 'storage/') || str_starts_with($value, 'campaigns/')) {
            $this->attributes['image'] = $value;

            return;
        }

        // If it's a URL, download and save
        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            $savedPath = self::downloadAndSaveImage($value);
            $this->attributes['image'] = $savedPath ?? $value;

            return;
        }

        // Otherwise, store as-is
        $this->attributes['image'] = $value;
    }

    public function getDonorsCountAttribute(): int
    {
        return $this->donations()->where('status', 'paid')->count();
    }

    public function getDaysLeftAttribute(): ?int
    {
        if (! $this->end_date) {
            return null;
        }

        return max(0, now()->diffInDays($this->end_date, false));
    }

    // ── Scopes ──

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    public static function categories(): array
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
