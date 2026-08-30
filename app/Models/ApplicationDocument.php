<?php

namespace App\Models;

use App\Domain\Onboarding\DocumentKind;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicationDocument extends Model
{
    protected $fillable = [
        'application_id', 'kind', 'original_name', 'path', 'mime', 'size', 'uploaded_by_user_id',
    ];

    protected $casts = [
        'kind' => DocumentKind::class,
        'size' => 'integer',
    ];

    public function getStoragePathAttribute(): string
    {
        return $this->path;
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by_user_id');
    }
}
