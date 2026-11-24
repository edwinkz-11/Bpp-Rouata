<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
use App\Models\Concerns\DeletesImages;
use App\Services\ImageService;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use DeletesImages, LogsActivity;

    public const TYPE_BPP = 'bpp';
    public const TYPE_DISTRICT = 'kecamatan';

    /** Kolom teks yang boleh diisi per jenis profil. */
    public const FIELDS = [
        self::TYPE_BPP => [
            'name', 'institution_name', 'description', 'history', 'vision', 'mission',
            'tasks_functions', 'address', 'phone', 'email',
        ],
        self::TYPE_DISTRICT => [
            'name', 'description', 'history', 'geography', 'environment', 'society', 'agriculture_potential',
        ],
    ];

    public array $imageColumns = ['logo', 'cover_image'];

    protected $fillable = [
        'type', 'name', 'institution_name', 'history', 'description', 'vision', 'mission',
        'tasks_functions', 'geography', 'environment', 'society', 'agriculture_potential',
        'address', 'phone', 'email', 'logo', 'cover_image',
    ];

    public static function forType(string $type): self
    {
        $defaults = $type === self::TYPE_BPP
            ? ['name' => 'BPP Pertanian Routa', 'institution_name' => 'Balai Penyuluhan Pertanian Kecamatan Routa']
            : ['name' => 'Kecamatan Routa'];

        return static::where('type', $type)->first() ?? new static($defaults + ['type' => $type]);
    }

    public static function bpp(): self
    {
        return static::forType(self::TYPE_BPP);
    }

    public static function district(): self
    {
        return static::forType(self::TYPE_DISTRICT);
    }

    public function getLogoUrlAttribute(): string
    {
        return ImageService::url($this->logo, 'images/logo-kementan.png');
    }

    public function getCoverUrlAttribute(): string
    {
        return ImageService::url($this->cover_image, 'images/routa-udara-2.jpg');
    }
}
