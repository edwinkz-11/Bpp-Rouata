<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/** Jalur jalan/jalur pertanian (tabel "routes"). Dinamai MapRoute agar tidak bentrok dengan facade Route. */
class MapRoute extends Model
{
    protected $table = 'routes';

    protected $fillable = [
        'start_location_id', 'end_location_id', 'created_by', 'name', 'description',
        'coordinates', 'verification_status', 'is_published',
    ];

    protected function casts(): array
    {
        return [
            'coordinates' => 'array',
            'is_published' => 'boolean',
        ];
    }

    public function startLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'start_location_id');
    }

    public function endLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'end_location_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('is_published', true)->where('verification_status', '!=', 'draft');
    }

    public function getPointsCountAttribute(): int
    {
        return count($this->coordinates ?? []);
    }
}
