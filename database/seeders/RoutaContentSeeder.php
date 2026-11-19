<?php

namespace Database\Seeders;

use App\Models\Commodity;
use App\Models\Profile;
use App\Models\Village;
use Illuminate\Database\Seeder;

/**
 * Isi awal Profil Kecamatan Routa dari sumber yang bisa ditelusuri.
 * Hanya mengisi kolom yang MASIH KOSONG, jadi aman dijalankan ulang dan tidak menimpa isian admin.
 *
 * Jalankan:  php artisan db:seed --class=RoutaContentSeeder
 *
 * Sumber: Wikipedia "Konawe Regency" (tabel kecamatan), BPS Sensus Penduduk 2010,
 * Perda Kab. Konawe No. 1 Tahun 2014, BSIP Sultra (23 September 2024), dan bagan struktur BPP Routa.
 * Angka resmi terbaru WAJIB dicek ke publikasi "Kecamatan Routa Dalam Angka" (BPS Konawe).
 */
class RoutaContentSeeder extends Seeder
{
    public function run(): void
    {
        $profile = Profile::firstOrCreate(['type' => 'kecamatan'], ['name' => 'Kecamatan Routa']);

        $text = [
            'description' => 'Routa adalah salah satu kecamatan di Kabupaten Konawe, Provinsi Sulawesi Tenggara, '
                .'dengan ibu kota kecamatan di Routa. Wilayahnya berada di daerah perbatasan Sulawesi Tenggara dan Sulawesi Tengah.',
            'geography' => "Luas wilayah sekitar 2.090 km² (angka publikasi berbeda-beda, mohon cek BPS terbaru).\n"
                ."Secara administrasi Routa terdiri dari 7 desa dan 1 kelurahan. Kode pos 93453.\n"
                .'Wilayah berbatasan dengan Provinsi Sulawesi Tengah.',
            'society' => 'Sensus Penduduk 2010 mencatat 1.892 jiwa (1.036 laki-laki dan 856 perempuan), seluruhnya berada di wilayah perdesaan. '
                .'Jumlah penduduk terbaru perlu diisi dari data BPS atau kantor kecamatan.',
            'agriculture_potential' => 'Kegiatan pertanian di Routa antara lain usaha padi sawah. Pada September 2024, Satgas Perluasan Areal Tanam BSIP Sultra '
                .'memantau areal persawahan di Kecamatan Routa, dan program pompanisasi telah menyalurkan 3 unit pompa ukuran 6 inci untuk petani. '
                .'Komoditas lain diisi petugas setelah datanya terverifikasi.',
        ];

        foreach ($text as $field => $value) {
            if (blank($profile->{$field})) {
                $profile->{$field} = $value;
            }
        }
        $profile->save();

        // Desa & kelurahan. Nama diambil dari bagan struktur BPP dan Perda Konawe No. 1/2014.
        // Daftar ini BELUM tentu lengkap: cocokkan dengan data resmi, lalu tambah/hapus di menu Data Desa.
        $villages = [
            'Lalomerui' => 'Wilayah binaan penyuluh BPP Routa. Profil desa belum diisi.',
            'Parudongka' => 'Wilayah binaan penyuluh BPP Routa. Profil desa belum diisi.',
            'Walandawe' => 'Wilayah binaan penyuluh BPP Routa. Profil desa belum diisi.',
            'Tanggola' => 'Wilayah binaan penyuluh BPP Routa. Profil desa belum diisi.',
            'Tirawonua' => 'Wilayah binaan penyuluh BPP Routa. Profil desa belum diisi.',
            'Puwiwirano' => 'Wilayah binaan penyuluh BPP Routa. Profil desa belum diisi.',
            'Wiau' => 'Desa di Kecamatan Routa (dibentuk lewat Perda Kabupaten Konawe No. 1 Tahun 2014). Profil desa belum diisi.',
            'Kelurahan Routa' => 'Kelurahan di Kecamatan Routa, wilayah binaan penyuluh BPP Routa. Profil belum diisi.',
        ];

        foreach ($villages as $name => $desc) {
            Village::firstOrCreate(['name' => $name], ['description' => $desc]);
        }

        // Komoditas awal: hanya yang ada sumbernya.
        Commodity::firstOrCreate(['name' => 'Padi Sawah'], [
            'category' => 'Tanaman Pangan',
            'description' => 'Padi sawah diusahakan petani di Kecamatan Routa.',
            'extra_info' => 'Sumber: BSIP Sultra, pemantauan areal persawahan Kecamatan Routa (23 September 2024). Luas tanam dan produksi diisi petugas.',
            'status' => 'active',
        ]);
    }
}
