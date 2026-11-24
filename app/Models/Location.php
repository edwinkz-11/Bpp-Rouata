<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
use App\Models\Concerns\DeletesImages;
use App\Services\ImageService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Location extends Model
{
    use DeletesImages, LogsActivity;

    public const CATEGORIES = [
        'kantor_bpp' => ['label' => 'Kantor BPP', 'color' => '#15803D', 'icon' => 'bi-house-door-fill'],
        'kantor_desa' => ['label' => 'Kantor Desa', 'color' => '#0F766E', 'icon' => 'bi-bank'],
        'kelompok_tani' => ['label' => 'Kelompok Tani', 'color' => '#CA8A04', 'icon' => 'bi-people-fill'],
        'lahan_pertanian' => ['label' => 'Lahan Pertanian', 'color' => '#65A30D', 'icon' => 'bi-flower1'],
        'lokasi_kegiatan' => ['label' => 'Lokasi Kegiatan', 'color' => '#2563EB', 'icon' => 'bi-megaphone-fill'],
        'lainnya' => ['label' => 'Lainnya', 'color' => '#64748B', 'icon' => 'bi-geo-alt-fill'],
    ];

    public const STATUSES = [
        'draft' => 'Draft',
        'unverified' => 'Belum Terverifikasi',
        'verified' => 'Terverifikasi',
    ];

    public array $imageColumns = ['image'];

    protected $fillable = [
        'village_id', 'created_by', 'name', 'category', 'description', 'extra_info',
        'latitude', 'longitude', 'image', 'verification_status', 'is_published',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'float',
            'longitude' => 'float',
            'is_published' => 'boolean',
        ];
    }

    public function village(): BelongsTo
    {
        return $this->belongsTo(Village::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /** Hanya yang diterbitkan dan bukan draft yang tampil ke pengunjung. */
    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('is_published', true)->where('verification_status', '!=', 'draft');
    }

    public function getCategoryLabelAttribute(): string
    {
        return self::CATEGORIES[$this->category]['label'] ?? 'Lainnya';
    }

    public function getImageUrlAttribute(): string
    {
        return ImageService::url($this->image);
    }
}
