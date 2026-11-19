<?php

namespace App\Models;

use App\Models\Concerns\DeletesImages;
use App\Services\ImageService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Commodity extends Model
{
    use DeletesImages;

    public const CATEGORIES = [
        'Tanaman Pangan' => 'Tanaman Pangan',
        'Perkebunan' => 'Perkebunan',
        'Hortikultura' => 'Hortikultura',
        'Peternakan' => 'Peternakan',
        'Komoditas Unggulan' => 'Komoditas Unggulan',
    ];

    public array $imageColumns = ['image'];

    protected $fillable = [
        'village_id', 'name', 'category', 'description', 'location_note', 'extra_info', 'image', 'status',
    ];

    public function village(): BelongsTo
    {
        return $this->belongsTo(Village::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    public function getImageUrlAttribute(): string
    {
        return ImageService::url($this->image);
    }

    /** Teks lokasi pengembangan: nama desa (jika ada) + catatan lokasi. */
    public function getLocationTextAttribute(): ?string
    {
        $parts = array_filter([$this->village?->name, $this->location_note]);

        return $parts ? implode(' — ', $parts) : null;
    }
}
