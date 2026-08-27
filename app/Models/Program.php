<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Program extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'icon'];

    public function schools(): BelongsToMany
    {
        return $this->belongsToMany(School::class, 'school_program');
    }
}
