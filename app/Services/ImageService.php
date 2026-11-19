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

    public function store(UploadedFile $file, string $dir): string
    {
        // Ekstensi diambil dari isi file (MIME), bukan dari nama file yang dikirim pengguna.
        $ext = strtolower($file->guessExtension() ?: 'jpg');
        $name = Str::uuid()->toString() . '.' . $ext;

        return (string) $file->storeAs($dir, $name, self::DISK);
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
