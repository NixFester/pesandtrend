<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Achievement extends Model
{
    protected $fillable = ['school_id', 'title', 'year'];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }
}
