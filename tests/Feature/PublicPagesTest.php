<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_semua_halaman_publik_terbuka_meski_database_kosong(): void
    {
        foreach (['home', 'district', 'activities.index', 'commodities.index', 'news', 'map', 'gallery', 'about',
            'documents.index', 'ppid', 'contact', 'privacy', 'search'] as $route) {
            $this->get(route($route))->assertOk();
        }
    }

    public function test_halaman_login_admin_terbuka(): void
    {
        $this->get(route('admin.login'))->assertOk();
    }

    public function test_halaman_tidak_dikenal_menampilkan_404(): void
    {
        $this->get('/halaman-yang-tidak-ada')->assertNotFound();
    }

    public function test_detail_kegiatan_draft_tidak_bisa_dibuka_publik(): void
    {
        $activity = Activity::create(['title' => 'Rahasia', 'slug' => 'rahasia', 'status' => 'draft']);

        $this->get(route('activities.show', $activity->slug))->assertNotFound();
    }

    public function test_judul_dengan_karakter_khusus_tidak_ter_escape_dua_kali(): void
    {
        $post = Post::create(['title' => 'Pupuk & Benih "Unggul"', 'slug' => 'pupuk', 'body' => 'x', 'status' => 'published', 'published_at' => now()->subMinute()]);

        $this->get(route('posts.show', $post->slug))
            ->assertOk()
            ->assertSee('<title>Pupuk &amp; Benih &quot;Unggul&quot; | ', false)
            ->assertDontSee('&amp;amp;', false);
    }

    public function test_aset_tidak_lagi_dimuat_dari_cdn_pihak_ketiga(): void
    {
        $html = $this->get(route('home'))->getContent();

        $this->assertStringNotContainsString('cdn.jsdelivr.net', $html);
        $this->assertStringNotContainsString('unpkg.com', $html);
        $this->assertStringNotContainsString('fonts.googleapis.com', $html);
    }

    public function test_aset_lokal_yang_dirujuk_benar_benar_ada(): void
    {
        foreach (['bootstrap/bootstrap.min.css', 'bootstrap/bootstrap.bundle.min.js', 'bootstrap-icons/bootstrap-icons.min.css',
            'bootstrap-icons/fonts/bootstrap-icons.woff2', 'leaflet/leaflet.css', 'leaflet/leaflet.js', 'poppins/poppins.css',
            'poppins/poppins-latin-400-normal.woff2'] as $file) {
            $this->assertFileExists(public_path('vendor/' . $file));
        }
    }
}
