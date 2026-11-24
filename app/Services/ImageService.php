<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Semua upload foto lewat sini: nama unik, disk "public", path disimpan di database.
 */
class ImageService
{
    public const DISK = 'public';

    /** Aturan validasi standar untuk satu file foto (JPG, JPEG, PNG, WEBP, maks 4 MB). */
    public const RULES = ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'];

    /** Sisi terpanjang maksimal (piksel) dan kualitas WebP hasil optimasi. */
    public const MAX_SIDE = 1600;
    public const WEBP_QUALITY = 82;

    public function store(UploadedFile $file, string $dir): string
    {
        if ($optimized = $this->storeOptimized($file, $dir)) {
            return $optimized;
        }

        // Ekstensi diambil dari isi file (MIME), bukan dari nama file yang dikirim pengguna.
        $ext = strtolower($file->guessExtension() ?: 'jpg');
        $name = Str::uuid()->toString() . '.' . $ext;

        return (string) $file->storeAs($dir, $name, self::DISK);
    }

    /**
     * Perkecil (maks. 1600 px) dan ubah ke WebP. Metadata EXIF, termasuk koordinat GPS, ikut terbuang.
     * Mengembalikan null jika GD tidak tersedia atau file tidak bisa diproses, lalu file asli disimpan apa adanya.
     */
    private function storeOptimized(UploadedFile $file, string $dir): ?string
    {
        if (! function_exists('imagewebp') || ! function_exists('imagecreatefromstring')) {
            return null;
        }

        $raw = @file_get_contents($file->getRealPath());
        $image = $raw === false ? false : @imagecreatefromstring($raw);

        if (! $image) {
            return null;
        }

        $image = $this->applyOrientation($image, $raw);
        $width = imagesx($image);
        $height = imagesy($image);

        if (max($width, $height) > self::MAX_SIDE) {
            $scale = self::MAX_SIDE / max($width, $height);
            $resized = imagescale($image, max(1, (int) round($width * $scale)), max(1, (int) round($height * $scale)));

            if ($resized) {
                $image = $resized;
            }
        }

        imagepalettetotruecolor($image);
        imagealphablending($image, false);
        imagesavealpha($image, true);

        ob_start();
        $ok = imagewebp($image, null, self::WEBP_QUALITY);
        $binary = (string) ob_get_clean();

        if (! $ok || $binary === '') {
            return null;
        }

        $path = trim($dir, '/') . '/' . Str::uuid()->toString() . '.webp';
        Storage::disk(self::DISK)->put($path, $binary);

        return $path;
    }

    /** Putar gambar JPEG sesuai orientasi EXIF supaya hasil WebP (tanpa EXIF) tidak miring. */
    private function applyOrientation(\GdImage $image, string $raw): \GdImage
    {
        if (! function_exists('exif_read_data') || ! str_starts_with($raw, "\xFF\xD8")) {
            return $image;
        }

        $exif = @exif_read_data('data://image/jpeg;base64,' . base64_encode($raw));
        $angle = match ($exif['Orientation'] ?? 1) {
            3 => 180,
            6 => -90,
            8 => 90,
            default => 0,
        };

        if ($angle !== 0 && ($rotated = imagerotate($image, $angle, 0))) {
            return $rotated;
        }

        return $image;
    }

    /** Simpan file baru dan hapus yang lama. Jika tidak ada file baru, path lama dikembalikan. */
    public function replace(?UploadedFile $file, ?string $old, string $dir): ?string
    {
        if (! $file) {
            return $old;
        }

        $new = $this->store($file, $dir);
        $this->delete($old);

        return $new;
    }

    public function delete(?string $path): void
    {
        if ($path && Storage::disk(self::DISK)->exists($path)) {
            Storage::disk(self::DISK)->delete($path);
        }
    }

    public static function url(?string $path, string $fallback = 'images/placeholder.svg'): string
    {
        return $path ? asset('storage/' . ltrim($path, '/')) : asset($fallback);
    }
}
