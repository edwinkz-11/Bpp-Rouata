<?php

namespace Database\Seeders;

use App\Models\Commodity;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

/**
 * Memasang foto komoditas (storage/app/public/komoditas/*.jpg) HANYA pada komoditas
 * yang belum punya foto, sehingga foto unggahan petugas tidak tertimpa.
 */
class KomoditasFotoSeeder extends Seeder
{
    public function run(): void
    {
        $peta = [
            'lada' => ['lada', 'merica'],
            'jagung' => ['jagung'],
            'cabai' => ['cabai', 'lombok', 'cabe'],
            'padi' => ['padi', 'sawah', 'beras'],
        ];

        foreach (Commodity::whereNull('image')->orWhere('image', '')->get() as $c) {
            $nama = strtolower($c->name);
            foreach ($peta as $berkas => $kata) {
                foreach ($kata as $k) {
                    if (str_contains($nama, $k) && Storage::disk('public')->exists("komoditas/$berkas.jpg")) {
                        $c->update(['image' => "komoditas/$berkas.jpg"]);
                        $this->command?->info("Foto dipasang: {$c->name}");
                        continue 3;
                    }
                }
            }
        }
    }
}
