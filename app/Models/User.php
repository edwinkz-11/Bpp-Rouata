<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, LogsActivity;

    public const ROLES = ['admin' => 'Admin', 'editor' => 'Editor'];

    protected $fillable = ['name', 'email', 'password', 'role'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class, 'created_by');
    }

    public function locations(): HasMany
    {
        return $this->hasMany(Location::class, 'created_by');
    }

    public function routes(): HasMany
    {
        return $this->hasMany(MapRoute::class, 'created_by');
    }
}
