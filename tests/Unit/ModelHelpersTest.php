<?php

namespace Tests\Unit;

use App\Models\ContactMessage;
use App\Models\Document;
use App\Models\Post;
use PHPUnit\Framework\TestCase;

class ModelHelpersTest extends TestCase
{
    public function test_ukuran_file_ditampilkan_dengan_satuan_yang_pas(): void
    {
        $label = fn (int $bytes) => (new Document(['file_size' => $bytes]))->size_label;

        $this->assertSame('512 B', $label(512));
        $this->assertSame('2 KB', $label(2048));
        $this->assertSame('1,5 MB', $label((int) (1.5 * 1048576)));
    }

    public function test_nama_unduhan_aman_dari_judul_dokumen(): void
    {
        $doc = new Document(['title' => '../../Laporan Kinerja "2025" <b>', 'file_ext' => 'pdf']);

        $this->assertSame('laporan-kinerja-2025-b.pdf', $doc->download_name);
        $this->assertSame('dokumen.pdf', (new Document(['title' => '???', 'file_ext' => 'pdf']))->download_name);
    }

    public function test_ringkasan_berita_memakai_excerpt_atau_memotong_isi_tanpa_html(): void
    {
        $this->assertSame('Ringkasan manual', (new Post(['excerpt' => 'Ringkasan manual', 'body' => 'x']))->summary);

        $summary = (new Post(['body' => '<p>' . str_repeat('kata ', 100) . '</p>']))->summary;
        $this->assertStringNotContainsString('<p>', $summary);
        $this->assertLessThanOrEqual(163, mb_strlen($summary), '160 karakter ditambah tanda elipsis');
        $this->assertStringEndsWith('...', $summary);
    }

    public function test_daftar_kategori_dokumen_ppid_adalah_bagian_dari_kategori_resmi(): void
    {
        $this->assertSame([], array_diff(Document::PPID_CATEGORIES, Document::CATEGORIES));
        $this->assertNotContains('php', Document::EXTENSIONS);
        $this->assertNotContains('html', Document::EXTENSIONS);
    }

    public function test_jenis_dan_status_pesan_lengkap(): void
    {
        $this->assertArrayHasKey('pengaduan', ContactMessage::TYPES);
        $this->assertSame(['new', 'read', 'resolved'], array_keys(ContactMessage::STATUSES));
    }
}
