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
}
