<?php

namespace App\Models;

use App\Services\ImageService;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    private static ?array $cache = null;

    public static function allKeyed(): array
    {
        return self::$cache ??= static::query()->pluck('value', 'key')->all();
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        $value = self::allKeyed()[$key] ?? null;

        return ($value === null || $value === '') ? $default : $value;
    }

    public static function put(string $key, ?string $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        self::$cache = null;
    }

    public static function strukturUrl(): string
    {
        return ImageService::url(static::get('struktur_image'), 'images/struktur-bpp-routa.jpg');
    }

    public static function videoUrl(): string
    {
        $path = static::get('video_tim');

        return $path ? asset('storage/'.$path) : asset('videos/tim-bpp-routa.mp4');
    }

    public static function videoPosterUrl(): string
    {
        return static::get('video_tim') ? asset('images/tim/1.jpg') : asset('videos/tim-bpp-routa-poster.jpg');
    }

    public static function heroUrl(): string
    {
        return ImageService::url(static::get('hero_image'), 'images/routa-udara-2.jpg');
    }

    /**
     * Pusat peta bawaan. Ini hanya sudut pandang awal (kawasan Sulawesi Tenggara),
     * BUKAN data lokasi. Jika sudah ada titik lokasi, peta otomatis menyesuaikan.
     * Admin dapat mengubahnya di Pengaturan Website.
     */
    public static function mapView(): array
    {
        return [
            'center' => [
                (float) static::get('map_center_lat', -3.6),
                (float) static::get('map_center_lng', 122.0),
            ],
            'zoom' => (int) static::get('map_zoom', 8),
        ];
    }

    public static function boundary(): ?array
    {
        $raw = static::get('boundary_geojson');

        return $raw ? json_decode($raw, true) : null;
    }
}
