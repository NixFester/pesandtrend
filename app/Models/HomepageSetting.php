<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomepageSetting extends Model
{
    protected $fillable = [
        'total_schools', 'total_students', 'total_cities', 'total_programs',
        'hero_title', 'hero_subtitle', 'hero_image',
    ];

    protected $casts = [
        'total_schools' => 'integer',
        'total_students' => 'integer',
        'total_cities' => 'integer',
        'total_programs' => 'integer',
    ];

    /**
     * Get the singleton homepage settings row, creating it if absent.
     */
    public static function current(): self
    {
        return static::firstOrCreate([], [
            'total_schools' => 8,
            'total_students' => 5200,
            'total_cities' => 25,
            'total_programs' => 13,
        ]);
    }

    public function getHeroImageUrlAttribute(): string
    {
        if (empty($this->hero_image)) {
            return asset('images/hero/architecture.jpg');
        }

        if (str_starts_with($this->hero_image, 'http://') || str_starts_with($this->hero_image, 'https://')) {
            return $this->hero_image;
        }

        if (str_starts_with($this->hero_image, 'images/')) {
            return asset($this->hero_image);
        }

        if (str_starts_with($this->hero_image, 'storage/')) {
            return asset($this->hero_image);
        }

        return \Illuminate\Support\Facades\Storage::disk('public')->url($this->hero_image);
    }
}
