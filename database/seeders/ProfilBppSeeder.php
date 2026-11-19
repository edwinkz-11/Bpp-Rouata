<?php

namespace Database\Seeders;

use App\Models\Profile;
use Illuminate\Database\Seeder;

/**
 * Draf teks umum untuk halaman Tentang BPP (bukan dokumen resmi).
 * Hanya mengisi kolom yang MASIH KOSONG, jadi isian petugas tidak tertimpa.
 * Ganti dengan teks resmi lewat Admin > Profil BPP.
 */
class ProfilBppSeeder extends Seeder
{
    public function run(): void
    {
        $profil = Profile::where('type', 'bpp')->first()
            ?? new Profile(['type' => 'bpp', 'name' => 'BPP Pertanian Routa', 'institution_name' => 'Balai Penyuluh Pertanian Kecamatan Routa']);

        $draf = [
            'description' => "BPP Pertanian Routa adalah Balai Penyuluh Pertanian yang melayani petani di Kecamatan Routa, Kabupaten Konawe, Sulawesi Tenggara.\n\nBPP menjadi tempat petani berkonsultasi, belajar teknologi budidaya, dan mendapat pendampingan dari penyuluh pertanian. Penyuluh turun langsung ke lahan melalui sosialisasi, Sekolah Lapang, kunjungan dan verifikasi lahan, serta penyaluran sarana produksi seperti pupuk.\n\nKomoditas yang saat ini banyak didampingi antara lain lada, jagung, dan cabai.",
            'vision' => 'Terwujudnya petani Kecamatan Routa yang mandiri, maju, dan sejahtera melalui penyuluhan pertanian yang dekat dan mudah dijangkau.',
            'mission' => "1. Meningkatkan pengetahuan dan keterampilan petani lewat penyuluhan, Sekolah Lapang, dan demonstrasi.\n2. Mendampingi petani menerapkan teknologi budidaya yang sesuai dengan kondisi wilayah Routa.\n3. Menguatkan kelompok tani dan kelembagaan petani.\n4. Menghubungkan petani dengan informasi, program, dan bantuan sarana produksi dari pemerintah.\n5. Mendokumentasikan potensi dan kegiatan pertanian Kecamatan Routa agar mudah diakses masyarakat.",
            'tasks_functions' => "1. Menyusun programa penyuluhan pertanian tingkat kecamatan bersama petani.\n2. Melaksanakan penyuluhan dan pendampingan petani di lapangan.\n3. Menyebarkan informasi dan teknologi pertanian yang tepat guna.\n4. Membina kelompok tani dan kelembagaan petani.\n5. Membantu pendataan potensi pertanian dan kebutuhan sarana produksi petani.\n6. Berkoordinasi dengan dinas pertanian dan instansi terkait.\n7. Menjadi tempat konsultasi bagi petani.\n8. Melaporkan kegiatan penyuluhan secara berkala.",
        ];

        foreach ($draf as $kolom => $isi) {
            if (blank($profil->{$kolom})) {
                $profil->{$kolom} = $isi;
            }
        }

        $profil->save();
    }
}
