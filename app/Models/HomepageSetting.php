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
}
