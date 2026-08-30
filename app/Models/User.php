<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role',
        'phone', 'whatsapp', 'address', 'lat', 'lng',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => 'string',
            'geocoded_at' => 'datetime',
            'lat' => 'decimal:7',
            'lng' => 'decimal:7',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasRole('admin');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin' || $this->hasRole('admin');
    }

    public function savedSchools(): BelongsToMany
    {
        return $this->belongsToMany(School::class, 'saved_schools')->withTimestamps();
    }

    public function applicationsCreated(): HasMany
    {
        return $this->hasMany(Application::class, 'created_by_user_id');
    }

    public function applicationsParent(): HasMany
    {
        return $this->hasMany(Application::class, 'parent_user_id');
    }
}
