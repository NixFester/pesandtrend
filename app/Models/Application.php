<?php

namespace App\Models;

use App\Domain\Onboarding\ApplicationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Application extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $hidden = [
        'student_nik_encrypted', 'student_nik_hash',
    ];

    protected $casts = [
        'status' => ApplicationStatus::class,
        'student_nik_encrypted' => 'encrypted',
        'student_birth_date' => 'date',
        'submitted_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Application $app) {
            if (empty($app->public_id)) {
                $app->public_id = (string) Str::ulid();
            }
            if (empty($app->status)) {
                $app->status = ApplicationStatus::Draft;
            }
        });
    }

    // ── Relations ──

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(ApplicationDocument::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(ApplicationPayment::class);
    }

    public function latestPayment(): HasOne
    {
        return $this->hasOne(ApplicationPayment::class)->latestOfMany();
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function parentUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'parent_user_id');
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by_user_id');
    }

    // ── Helpers ──

    public function transitionTo(ApplicationStatus $newStatus): void
    {
        if (! $this->status->canTransitionTo($newStatus)) {
            throw new \InvalidArgumentException(
                "Cannot transition from {$this->status->value} to {$newStatus->value}."
            );
        }

        $this->status = $newStatus;
        $this->save();
    }
}
