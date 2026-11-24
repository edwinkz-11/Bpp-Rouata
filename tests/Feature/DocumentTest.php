<?php

namespace Tests\Feature;

use App\Models\Document;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DocumentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake(Document::DISK);
    }

    private function create(array $override = []): Document
    {
        $path = 'dokumen/contoh.pdf';
        Storage::disk(Document::DISK)->put($path, '%PDF-1.4 contoh');

        return Document::create(array_merge([
            'title' => 'Laporan Kinerja 2025', 'category' => 'Laporan Kinerja', 'year' => 2025,
            'file_path' => $path, 'file_ext' => 'pdf', 'file_size' => 15, 'is_published' => true,
        ], $override));
    }

    public function test_tamu_tidak_bisa_mengelola_dokumen(): void
    {
        $this->get(route('admin.documents.index'))->assertRedirect(route('login'));
        $this->post(route('admin.documents.store'), [])->assertRedirect(route('login'));
    }

    public function test_pengelola_mengunggah_dokumen_pdf(): void
    {
        $this->actingAs($this->editor())->post(route('admin.documents.store'), [
            'title' => 'Programa Penyuluhan 2026', 'category' => 'Programa Penyuluhan', 'year' => 2026, 'is_published' => 1,
            'file' => UploadedFile::fake()->create('programa.pdf', 200, 'application/pdf'),
        ])->assertRedirect(route('admin.documents.index'));

        $doc = Document::firstOrFail();
        $this->assertSame('pdf', $doc->file_ext);
        Storage::disk(Document::DISK)->assertExists($doc->file_path);
        $this->assertStringStartsWith('dokumen/', $doc->file_path);
        $this->assertStringNotContainsString('programa', $doc->file_path, 'Nama file asli tidak boleh dipakai di server');
    }

    public function test_file_berbahaya_ditolak(): void
    {
        $this->actingAs($this->editor())->post(route('admin.documents.store'), [
            'title' => 'Shell', 'category' => 'Lainnya', 'is_published' => 1,
            'file' => UploadedFile::fake()->createWithContent('shell.php', '<?php system($_GET["c"]);'),
        ])->assertSessionHasErrors('file');

        $this->assertDatabaseCount('documents', 0);
    }

    public function test_file_php_berkedok_pdf_ditolak(): void
    {
        // File sungguhan (bukan fake) supaya validator benar-benar membaca isinya, bukan hanya nama.
        $tmp = tempnam(sys_get_temp_dir(), 'doc');
        file_put_contents($tmp, '<?php system($_GET["c"]);');

        $this->actingAs($this->editor())->post(route('admin.documents.store'), [
            'title' => 'Palsu', 'category' => 'Lainnya', 'is_published' => 1,
            'file' => new UploadedFile($tmp, 'laporan.pdf', 'application/pdf', null, true),
        ])->assertSessionHasErrors('file');

        $this->assertDatabaseCount('documents', 0);
    }

    public function test_pdf_asli_diterima_walau_dikirim_dengan_file_sungguhan(): void
    {
        $tmp = tempnam(sys_get_temp_dir(), 'doc');
        file_put_contents($tmp, "%PDF-1.4\n1 0 obj<</Type/Catalog>>endobj\ntrailer<</Root 1 0 R>>\n%%EOF");

        $this->actingAs($this->editor())->post(route('admin.documents.store'), [
            'title' => 'Asli', 'category' => 'Lainnya', 'is_published' => 1,
            'file' => new UploadedFile($tmp, 'asli.pdf', 'application/pdf', null, true),
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseCount('documents', 1);
    }

    public function test_file_lebih_dari_10_mb_ditolak(): void
    {
        $this->actingAs($this->editor())->post(route('admin.documents.store'), [
            'title' => 'Besar', 'category' => 'Lainnya', 'is_published' => 1,
            'file' => UploadedFile::fake()->create('besar.pdf', 10241, 'application/pdf'),
        ])->assertSessionHasErrors('file');
    }

    public function test_kategori_tidak_dikenal_ditolak(): void
    {
        $this->actingAs($this->editor())->post(route('admin.documents.store'), [
            'title' => 'X', 'category' => 'Ngawur', 'is_published' => 1,
            'file' => UploadedFile::fake()->create('a.pdf', 10, 'application/pdf'),
        ])->assertSessionHasErrors('category');
    }

    public function test_unduhan_menaikkan_hitungan_dan_memakai_nama_aman(): void
    {
        $doc = $this->create();

        $response = $this->get(route('documents.download', $doc))->assertOk();

        $this->assertStringContainsString('laporan-kinerja-2025.pdf', $response->headers->get('Content-Disposition'));
        $this->assertSame(1, $doc->fresh()->downloads);
    }

    public function test_dokumen_yang_disembunyikan_tidak_bisa_diunduh_dan_tidak_muncul(): void
    {
        $doc = $this->create(['is_published' => false]);

        $this->get(route('documents.download', $doc))->assertNotFound();
        $this->get(route('documents.index'))->assertDontSee('Laporan Kinerja 2025');
    }

    public function test_file_tidak_bisa_diakses_langsung_lewat_url_storage(): void
    {
        $doc = $this->create();

        $this->assertFalse(Storage::disk('public')->exists($doc->file_path), 'Dokumen harus di disk privat');
    }

    public function test_daftar_publik_bisa_dicari_dan_difilter(): void
    {
        $this->create();
        $this->create(['title' => 'Formulir Permohonan', 'category' => 'Formulir', 'year' => 2024]);

        $this->get(route('documents.index', ['q' => 'Formulir']))->assertSee('Formulir Permohonan')->assertDontSee('Laporan Kinerja 2025');
        $this->get(route('documents.index', ['kategori' => 'Laporan Kinerja']))->assertSee('Laporan Kinerja 2025')->assertDontSee('Formulir Permohonan');
        $this->get(route('documents.index', ['tahun' => 2024]))->assertSee('Formulir Permohonan')->assertDontSee('Laporan Kinerja 2025');
    }

    public function test_halaman_ppid_mengelompokkan_dokumen_informasi(): void
    {
        $this->create(['title' => 'Daftar Informasi Berkala', 'category' => 'Informasi Berkala']);
        $this->create(['title' => 'Laporan Biasa', 'category' => 'Laporan Kinerja']);

        $this->get(route('ppid'))->assertSee('Daftar Informasi Berkala')->assertDontSee('Laporan Biasa');
    }

    public function test_hapus_dokumen_ikut_menghapus_file(): void
    {
        $doc = $this->create();

        $this->actingAs($this->admin())->delete(route('admin.documents.destroy', $doc))->assertRedirect();

        $this->assertDatabaseCount('documents', 0);
        Storage::disk(Document::DISK)->assertMissing('dokumen/contoh.pdf');
    }

    public function test_ganti_file_menghapus_file_lama_dan_mengatur_ulang_unduhan(): void
    {
        $doc = $this->create(['downloads' => 9]);

        $this->actingAs($this->admin())->put(route('admin.documents.update', $doc), [
            'title' => $doc->title, 'category' => $doc->category, 'year' => 2025, 'is_published' => 1,
            'file' => UploadedFile::fake()->create('baru.pdf', 50, 'application/pdf'),
        ])->assertRedirect();

        $doc->refresh();
        Storage::disk(Document::DISK)->assertMissing('dokumen/contoh.pdf');
        Storage::disk(Document::DISK)->assertExists($doc->file_path);
        $this->assertSame(0, $doc->downloads);
    }
}
