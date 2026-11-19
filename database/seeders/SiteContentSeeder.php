<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class SiteContentSeeder extends Seeder
{
    public function run(): void
    {
        // Hanya nama & slogan dari brief. Alamat, telepon, email, sejarah, dll. diisi admin.
        Profile::firstOrCreate(['type' => 'bpp'], [
            'name' => 'BPP Pertanian Routa',
            'institution_name' => 'Balai Penyuluh Pertanian Kecamatan Routa',
        ]);

        Profile::firstOrCreate(['type' => 'kecamatan'], [
            'name' => 'Kecamatan Routa',
        ]);

        $defaults = [
            'slogan' => 'Membangun Pertanian Routa yang Maju, Mandiri, dan Berkelanjutan.',
            'instagram_url' => 'https://www.instagram.com/bpp.routa/',
        ];

        foreach ($defaults as $key => $value) {
            if (Setting::where('key', $key)->doesntExist()) {
                Setting::put($key, $value);
            }
        }
    }
}
