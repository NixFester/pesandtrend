<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SchoolPhoto extends Model
{
    protected $fillable = [
        'school_id', 'path', 'caption', 'sort',
    ];

    protected $casts = [
        'sort' => 'integer',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function getUrlAttribute(): string
    {
        if (empty($this->path)) {
            return '';
        }

        if (str_starts_with($this->path, 'http://') || str_starts_with($this->path, 'https://')) {
            return $this->path;
        }

        if (str_starts_with($this->path, 'images/')) {
            return asset($this->path);
        }

        if (str_starts_with($this->path, 'storage/')) {
            return asset($this->path);
        }

        return \Illuminate\Support\Facades\Storage::disk('public')->url($this->path);
    }
}
