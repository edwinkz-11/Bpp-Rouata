<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\Commodity;
use App\Models\Document;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_pencarian_menemukan_dari_semua_jenis_konten_terbit(): void
    {
        Post::create(['title' => 'Panen Jagung Perdana', 'slug' => 'jagung-berita', 'body' => 'x', 'status' => 'published', 'published_at' => now()->subMinute()]);
        Activity::create(['title' => 'Pelatihan Jagung Hibrida', 'slug' => 'jagung-kegiatan', 'status' => 'published']);
        Document::create(['title' => 'Panduan Budidaya Jagung', 'category' => 'Lainnya', 'file_path' => 'dokumen/x.pdf', 'file_ext' => 'pdf', 'is_published' => true]);
        Commodity::create(['name' => 'Jagung Manis', 'category' => 'Tanaman Pangan', 'status' => 'active']);

        $this->get(route('search', ['q' => 'jagung']))
            ->assertOk()
            ->assertSee('Panen Jagung Perdana')->assertSee('Pelatihan Jagung Hibrida')
            ->assertSee('Panduan Budidaya Jagung')->assertSee('Jagung Manis')
            ->assertSee('4 hasil ditemukan');
    }

    public function test_konten_draft_atau_disembunyikan_tidak_ikut_hasil(): void
    {
        Activity::create(['title' => 'Kegiatan Kedelai Rahasia', 'slug' => 'kedelai', 'status' => 'draft']);
        Document::create(['title' => 'Dokumen Kedelai Internal', 'category' => 'Lainnya', 'file_path' => 'dokumen/y.pdf', 'file_ext' => 'pdf', 'is_published' => false]);
        Post::create(['title' => 'Berita Kedelai Draft', 'slug' => 'kedelai-draft', 'body' => 'x', 'status' => 'draft']);

        $this->get(route('search', ['q' => 'kedelai']))->assertSee('Tidak ada hasil')->assertDontSee('Rahasia')->assertDontSee('Internal');
    }

    public function test_kata_kunci_terlalu_pendek_ditolak_dengan_pesan(): void
    {
        $this->get(route('search', ['q' => 'ab']))->assertOk()->assertSee('terlalu pendek');
    }

    public function test_karakter_wildcard_diperlakukan_sebagai_teks_biasa(): void
    {
        Activity::create(['title' => 'Kegiatan Biasa', 'slug' => 'biasa', 'status' => 'published']);

        $this->get(route('search', ['q' => '%%%']))->assertOk()->assertDontSee('Kegiatan Biasa');
    }

    public function test_input_pencarian_di_escape(): void
    {
        $this->get(route('search', ['q' => '<script>alert(1)</script>']))->assertDontSee('<script>alert(1)</script>', false);
    }

    public function test_pencarian_dibatasi_30_permintaan_per_menit(): void
    {
        for ($i = 0; $i < 30; $i++) {
            $this->get(route('search', ['q' => 'padi']))->assertOk();
        }

        $this->get(route('search', ['q' => 'padi']))->assertStatus(429);
    }
}
