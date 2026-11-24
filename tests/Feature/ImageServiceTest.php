<?php

namespace Tests\Feature;

use App\Services\ImageService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImageServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake(ImageService::DISK);
    }

    public function test_foto_besar_diperkecil_ke_maksimal_1600_px_dan_jadi_webp(): void
    {
        $path = app(ImageService::class)->store(UploadedFile::fake()->image('besar.jpg', 4000, 3000), 'uji');

        $this->assertStringEndsWith('.webp', $path);
        [$w, $h, $type] = getimagesizefromstring(Storage::disk(ImageService::DISK)->get($path));
        $this->assertSame(IMAGETYPE_WEBP, $type);
        $this->assertSame(1600, $w);
        $this->assertSame(1200, $h, 'Rasio gambar harus tetap');
    }

    public function test_foto_kecil_tidak_diperbesar(): void
    {
        $path = app(ImageService::class)->store(UploadedFile::fake()->image('kecil.png', 400, 300), 'uji');

        [$w, $h] = getimagesizefromstring(Storage::disk(ImageService::DISK)->get($path));
        $this->assertSame([400, 300], [$w, $h]);
    }

    public function test_png_transparan_tetap_transparan(): void
    {
        $img = imagecreatetruecolor(50, 50);
        imagealphablending($img, false);
        imagesavealpha($img, true);
        imagefill($img, 0, 0, imagecolorallocatealpha($img, 0, 0, 0, 127));
        $tmp = tempnam(sys_get_temp_dir(), 'png');
        imagepng($img, $tmp);

        $path = app(ImageService::class)->store(new UploadedFile($tmp, 'logo.png', 'image/png', null, true), 'uji');

        $out = imagecreatefromstring(Storage::disk(ImageService::DISK)->get($path));
        $this->assertSame(127, (imagecolorat($out, 10, 10) >> 24) & 0x7F);
    }

    public function test_nama_file_acak_dan_ekstensi_bukan_dari_pengguna(): void
    {
        $path = app(ImageService::class)->store(UploadedFile::fake()->image('../../evil.php.jpg', 100, 100), 'uji');

        $this->assertMatchesRegularExpression('#^uji/[0-9a-f-]{36}\.webp$#', $path);
    }

    public function test_file_rusak_jatuh_kembali_ke_penyimpanan_asli_tanpa_error(): void
    {
        $file = UploadedFile::fake()->createWithContent('rusak.jpg', 'bukan gambar sungguhan');

        $path = app(ImageService::class)->store($file, 'uji');

        Storage::disk(ImageService::DISK)->assertExists($path);
    }

    public function test_replace_menghapus_file_lama(): void
    {
        $service = app(ImageService::class);
        $old = $service->store(UploadedFile::fake()->image('a.jpg', 100, 100), 'uji');

        $new = $service->replace(UploadedFile::fake()->image('b.jpg', 100, 100), $old, 'uji');

        Storage::disk(ImageService::DISK)->assertMissing($old);
        Storage::disk(ImageService::DISK)->assertExists($new);
        $this->assertSame($new, $service->replace(null, $new, 'uji'), 'Tanpa file baru, path lama dikembalikan');
    }
}
