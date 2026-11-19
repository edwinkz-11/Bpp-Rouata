<?php

namespace Database\Seeders;

use App\Models\ActivityCategory;
use Illuminate\Database\Seeder;

class ActivityCategorySeeder extends Seeder
{
    public function run(): void
    {
        $names = [
            'Penyuluhan Pertanian',
            'Kunjungan Lapangan',
            'Penanaman',
            'Panen',
            'Pelatihan Pertanian',
            'Pertemuan Kelompok Tani',
            'Kegiatan Lainnya',
        ];

        foreach ($names as $name) {
            ActivityCategory::firstOrCreate(['name' => $name]);
        }
    }
}
