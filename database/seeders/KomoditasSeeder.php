<?php

namespace Database\Seeders;

use App\Models\Commodity;
use Illuminate\Database\Seeder;

/**
 * Komoditas yang terdokumentasi di kegiatan BPP Routa (banner dan teks foto):
 * rehabilitasi lada, SL Tematik Jagung, lahan cabai. Tanpa angka luas/produksi:
 * lengkapi sisanya lewat Admin > Potensi/Komoditas.
 * Aman dijalankan berulang (dicocokkan berdasarkan nama).
 */
class KomoditasSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['Lada', 'Perkebunan', 'Kegiatan rehabilitasi tanaman lada di Kecamatan Routa (Desa Tirawonua, Parudongka, dan sekitarnya), termasuk penyaluran pupuk organik dan pembuatan lubang tanam. Luas dan produksi diisi petugas.'],
            ['Jagung', 'Tanaman Pangan', 'Sekolah Lapang (SL) Tematik Jagung dan Farm Field Day di Kecamatan Routa. Luas tanam dan produksi diisi petugas.'],
            ['Cabai', 'Hortikultura', 'Lahan cabai petani di wilayah binaan BPP Routa. Lokasi, luas, dan produksi diisi petugas.'],
        ];

        foreach ($rows as [$name, $cat, $desc]) {
            Commodity::firstOrCreate(['name' => $name], [
                'category' => $cat,
                'description' => $desc,
                'status' => 'active',
            ]);
        }
    }
}
