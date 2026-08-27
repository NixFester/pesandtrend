<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class School extends Model
{
    protected $fillable = [
        'name', 'slug', 'type', 'jenjang', 'city', 'province', 'address',
        'short_desc', 'description', 'rating', 'reviews_count', 'students_count',
        'teacher_ratio', 'founded_year', 'is_boarding', 'registration_open',
        'is_verified', 'is_featured', 'accreditation', 'image', 'badge', 'tags',
        'alumni_stats', 'uang_pangkal', 'spp_monthly', 'asrama_monthly',
        'seragam_fee', 'ekskul_fee', 'study_tour_fee',
    ];

    protected $casts = [
        'jenjang' => 'array',
        'tags' => 'array',
        'alumni_stats' => 'array',
        'rating' => 'float',
        'is_boarding' => 'boolean',
        'registration_open' => 'boolean',
        'is_verified' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function facilities(): BelongsToMany
    {
        return $this->belongsToMany(Facility::class, 'school_facility');
    }

    public function programs(): BelongsToMany
    {
        return $this->belongsToMany(Program::class, 'school_program');
    }

    public function savedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'saved_schools');
    }

    public function achievements(): HasMany
    {
        return $this->hasMany(Achievement::class);
    }

    public function getMonthlyTotalAttribute(): int
    {
        return $this->spp_monthly + $this->asrama_monthly;
    }

    public function getFirstYearTotalAttribute(): int
    {
        return $this->uang_pangkal
            + $this->seragam_fee
            + $this->ekskul_fee
            + $this->study_tour_fee
            + (12 * ($this->spp_monthly + $this->asrama_monthly));
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['q'] ?? false, fn ($q, $search) => $q->where(function ($qq) use ($search) {
            $qq->where('name', 'like', "%{$search}%")
                ->orWhere('city', 'like', "%{$search}%")
                ->orWhere('province', 'like', "%{$search}%")
                ->orWhere('short_desc', 'like', "%{$search}%");
        }));

        $query->when($filters['kota'] ?? false, fn ($q, $city) => $q->where('city', $city));

        $query->when($filters['jenjang'] ?? false, function ($q, $jenjang) {
            $q->whereJsonContains('jenjang', $jenjang);
        });

        $query->when(($filters['tipe'] ?? false) === 'berasrama', fn ($q) => $q->where('is_boarding', true));

        $query->when(($filters['tipe'] ?? false) === 'terpadu', fn ($q) => $q->where('type', 'Sekolah Islam Terpadu'));

        $query->when(($filters['tipe'] ?? false) === 'pesantren', fn ($q) => $q->where('type', 'like', 'Pesantren%'));

        return $query;
    }

    public function scopeSorted($query, ?string $sort)
    {
        return match ($sort) {
            'rating' => $query->orderByDesc('rating')->orderByDesc('reviews_count'),
            'murah' => $query->orderBy('spp_monthly'),
            'populer' => $query->orderByDesc('students_count'),
            default => $query->orderByDesc('is_featured')->orderByDesc('rating'),
        };
    }
}
