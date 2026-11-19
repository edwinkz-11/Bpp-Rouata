<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\ActivityCategory;
use App\Models\Village;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

/**
 * Memasukkan 21 foto dokumentasi BPP Routa sebagai kegiatan (album) di Galeri.
 * Judul/tanggal hanya dari teks yang tertera di foto (watermark Timemark/geotag).
 * Idempoten: album yang sudah ada (slug sama) dilewati.
 * Album yang lokasinya belum pasti dibuat berstatus DRAFT (tidak tampil publik).
 */
class KegiatanFotoSeeder extends Seeder
{
    public function run(): void
    {
        $albums = [
            [
                'title' => 'Penyaluran Pupuk Organik Rehabilitasi Tanaman Lada, Desa Tirawonua',
                'cat' => 'Kunjungan Lapangan', 'village' => 'Tirawonua', 'date' => '2026-11-02',
                'location' => 'Poktan Madeceng dan Poktan Siparennu, Desa Tirawonua, Kec. Routa',
                'desc' => 'Dokumentasi penyaluran pupuk organik untuk kegiatan rehabilitasi tanaman lada di Poktan Madeceng dan Poktan Siparennu, Desa Tirawonua (2 November 2026).',
                'status' => 'published',
                'photos' => [
                    1 => 'Penyaluran pupuk organik, Poktan Madeceng, Desa Tirawonua',
                    7 => 'Bongkar muat pupuk organik, Poktan Madeceng',
                    2 => 'Kunjungan harian BPP Routa: penyaluran pupuk, Poktan Siparennu',
                ],
            ],
            [
                'title' => 'Pembuatan Lubang Tanam Rehabilitasi Tanaman Lada',
                'cat' => 'Penanaman', 'village' => null, 'date' => '2026-10-12',
                'location' => 'Poktan Sipatuo dan Poktan Padaidi, Kec. Routa',
                'desc' => 'Dokumentasi sebelum dan sesudah pembuatan lubang tanam di kebun lada Poktan Sipatuo dan Poktan Padaidi (Oktober 2026), serta kerja pembuatan lubang tanam pada 30 Oktober dan 9 November 2026.',
                'status' => 'published',
                'photos' => [
                    4 => 'Sebelum pembuatan lubang tanam, Poktan Sipatuo (12 Okt 2026)',
                    5 => 'Sesudah pembuatan lubang tanam, Poktan Sipatuo (15 Okt 2026)',
                    6 => 'Sebelum pembuatan lubang tanam, Poktan Padaidi (12 Okt 2026)',
                    8 => 'Pembuatan lubang tanam di kebun lada (30 Okt 2026)',
                    3 => 'Pembuatan lubang tanam di kebun lada (9 Nov 2026)',
                ],
            ],
            [
                'title' => 'Verifikasi Tanaman Lada, Desa Parudongka',
                'cat' => 'Kunjungan Lapangan', 'village' => 'Parudongka', 'date' => '2026-02-08',
                'location' => 'Desa Parudongka, Kec. Routa, Kab. Konawe',
                'desc' => 'Kegiatan verifikasi tanaman lada di Desa Parudongka (8 Februari 2026).',
                'status' => 'published',
                'photos' => [16 => 'Verifikasi tanaman lada, Desa Parudongka'],
            ],
            [
                'title' => 'Sosialisasi Sekolah Lapang (SL) Tematik, BPP Kec. Routa',
                'cat' => 'Pelatihan Pertanian', 'village' => null, 'date' => '2025-08-12',
                'location' => 'Kec. Routa, Kab. Konawe',
                'desc' => 'Sosialisasi Sekolah Lapang Tematik Dinas Tanaman Pangan, Hortikultura dan Perkebunan Kab. Konawe bersama petani di wilayah BPP Routa (12 Agustus 2025).',
                'status' => 'published',
                'photos' => [18 => 'Sosialisasi SL Tematik, BPP Kec. Routa'],
            ],
            [
                'title' => 'Farm Field Day SL Tematik Jagung, Kec. Routa',
                'cat' => 'Pelatihan Pertanian', 'village' => 'Parudongka', 'date' => null,
                'location' => 'Kec. Routa, Kab. Konawe',
                'desc' => 'Farm Field Day (FFD) Sekolah Lapang Tematik Jagung bersama petani, Balai Penyuluhan Pertanian Kec. Routa.',
                'status' => 'published',
                'photos' => [
                    19 => 'Farm Field Day SL Tematik Jagung (1)',
                    20 => 'Farm Field Day SL Tematik Jagung (2)',
                    21 => 'Farm Field Day SL Tematik Jagung (3)',
                ],
            ],
            [
                'title' => 'Temu Usaha BPP Routa Tahun 2025',
                'cat' => 'Pertemuan Kelompok Tani', 'village' => null, 'date' => null,
                'location' => 'Kec. Routa, Kab. Konawe',
                'desc' => 'Temu Usaha Balai Penyuluhan Pertanian (BPP) Routa Tahun 2025, Dinas Tanaman Pangan, Hortikultura dan Perkebunan Kab. Konawe.',
                'status' => 'published',
                'photos' => [17 => 'Temu Usaha BPP Routa 2025'],
            ],
            [
                'title' => 'Dokumentasi Kegiatan Lapangan BPP Routa',
                'cat' => 'Kegiatan Lainnya', 'village' => null, 'date' => null,
                'location' => 'Kec. Routa, Kab. Konawe',
                'desc' => 'Kumpulan dokumentasi kegiatan lapangan penyuluh BPP Routa. Lengkapi keterangan tiap foto lewat menu admin.',
                'status' => 'published',
                'photos' => [
                    15 => 'Pendampingan penyuluh di kebun lada',
                    11 => 'Kunjungan lahan cabai',
                    12 => 'Kegiatan di lahan terbuka (9 Mei 2026)',
                    9 => 'Pembibitan (polybag) di area persemaian',
                ],
            ],
            // --- DRAFT: cek dulu sebelum diterbitkan lewat admin ---
            [
                'title' => 'Kunjungan Tim Ditjen Perkebunan, Poktan Karya Mandiri',
                'cat' => 'Kunjungan Lapangan', 'village' => null, 'date' => '2026-04-30',
                'location' => 'Desa Nekudu (cek: koordinat foto jauh dari Routa)',
                'desc' => 'Teks foto: "Tim dari Dirjen Perkebunan Poktan Karya Mandiri Desa Nekudu". Pastikan wilayah ini binaan BPP Routa sebelum diterbitkan.',
                'status' => 'draft',
                'photos' => [10 => 'Tim dari Ditjen Perkebunan, Poktan Karya Mandiri, Desa Nekudu'],
            ],
            [
                'title' => 'Kunjungan Lapang Lahan Cabai, Jagung dan Sumber Air',
                'cat' => 'Kunjungan Lapangan', 'village' => null, 'date' => null,
                'location' => '',
                'desc' => 'Foto tanpa keterangan; ada peserta berseragam perusahaan. Isi pihak dan lokasi kegiatan sebelum diterbitkan.',
                'status' => 'draft',
                'photos' => [
                    14 => 'Kunjungan lahan cabai dan jagung',
                    13 => 'Kunjungan area kolam/sumber air',
                ],
            ],
        ];

        $disk = Storage::disk('public');
        $dir = __DIR__ . '/assets/kegiatan';

        foreach ($albums as $a) {
            $slug = Activity::makeSlug($a['title']);
            $existing = Activity::where('title', $a['title'])->first();
            if ($existing) {
                continue;
            }

            $cat = ActivityCategory::firstOrCreate(['name' => $a['cat']]);
            $village = $a['village'] ? Village::where('name', $a['village'])->first() : null;

            $firstNo = array_key_first($a['photos']);
            $coverPath = 'kegiatan/seed-' . $slug . '.jpg';
            $disk->put($coverPath, file_get_contents(sprintf('%s/f%02d.jpg', $dir, $firstNo)));

            $activity = Activity::create([
                'category_id' => $cat->id,
                'village_id' => $village?->id,
                'title' => $a['title'],
                'slug' => $slug,
                'description' => $a['desc'],
                'location_name' => $a['location'] ?: null,
                'activity_date' => $a['date'],
                'cover_image' => $coverPath,
                'status' => $a['status'],
            ]);

            foreach ($a['photos'] as $no => $caption) {
                $path = sprintf('galeri/seed-f%02d.jpg', $no);
                $disk->put($path, file_get_contents(sprintf('%s/f%02d.jpg', $dir, $no)));
                $activity->photos()->create(['image_path' => $path, 'caption' => $caption]);
            }
        }
    }
}
