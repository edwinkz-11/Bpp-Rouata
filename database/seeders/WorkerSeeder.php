<?php

namespace Database\Seeders;

use App\Models\ExtensionWorker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

/**
 * Data penyuluh diambil dari bagan struktur BPP Routa (STRUKTUR_BPP_ROUTA1.pdf)
 * dan kartu perkenalan (CARD_BPP_ROUTA). Admin dapat mengubahnya di dashboard.
 * Aman dijalankan berulang: data dicocokkan berdasarkan nama.
 */
class WorkerSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['agustianus', 'Agustianus, S.TP', 'Koordinator BPP', 'Penyuluh Desa Lalomerui'],
            ['fajar', 'Ip. Fajar Abdullah Towande, S.P', 'Progremer', 'Penyuluh Desa Parudongka'],
            ['eriyanto', 'Eriyanto Uma, S.P', 'Admin', 'Penyuluh Desa Walandawe'],
            ['rian', 'Rian, S.P', 'Anggota BPP', 'Penyuluh Desa Tanggola'],
            ['samrin', 'Samrin, S.P', 'Anggota BPP', 'Penyuluh Desa Tirawonua'],
            ['ihwal', 'Ihwal Ardiansyah, SP.', 'Anggota BPP', 'Penyuluh Kelurahan Routa'],
            ['ilham', 'Ilham, S.P', 'Anggota BPP', 'Penyuluh Desa Puwiwirano'],
        ];

        foreach ($rows as $i => [$file, $name, $position, $area]) {
            $worker = ExtensionWorker::firstOrNew(['name' => $name]);
            $worker->fill(['position' => $position, 'working_area' => $area, 'sort_order' => $i + 1]);

            $src = database_path("seeders/assets/penyuluh/{$file}.jpg");
            if (! $worker->photo && File::exists($src)) {
                $path = "penyuluh/{$file}.jpg";
                Storage::disk('public')->put($path, File::get($src));
                $worker->photo = $path;
            }

            $worker->save();
        }
    }
}
