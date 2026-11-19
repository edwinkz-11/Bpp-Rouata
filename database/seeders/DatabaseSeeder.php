<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Seeder hanya mengisi data dasar yang pasti benar (akun admin, kategori kegiatan,
 * nama BPP, tautan Instagram). TIDAK ada data fiktif: desa, komoditas, lokasi dan
 * jalur diisi petugas lewat dashboard admin.
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            ActivityCategorySeeder::class,
            SiteContentSeeder::class,
            WorkerSeeder::class,
            KegiatanFotoSeeder::class,
        ]);
    }
}
