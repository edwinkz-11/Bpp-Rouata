<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoTest extends TestCase
{
    use RefreshDatabase;

    public function test_robots_memblokir_admin_dan_menunjuk_sitemap(): void
    {
        $body = $this->get('/robots.txt')->assertOk()->getContent();

        $this->assertStringContainsString('Disallow: /admin', $body);
        $this->assertStringContainsString('Sitemap: ' . url('/sitemap.xml'), $body);
    }

    public function test_sitemap_memuat_halaman_terbit_dan_tidak_memuat_draft(): void
    {
        Activity::create(['title' => 'Panen Raya', 'slug' => 'panen-raya', 'status' => 'published']);
        Activity::create(['title' => 'Masih Draft', 'slug' => 'masih-draft', 'status' => 'draft']);
        Post::create(['title' => 'Berita Terbit', 'slug' => 'berita-terbit', 'body' => 'x', 'status' => 'published', 'published_at' => now()->subHour()]);
        Post::create(['title' => 'Berita Terjadwal', 'slug' => 'berita-terjadwal', 'body' => 'x', 'status' => 'published', 'published_at' => now()->addDay()]);

        $response = $this->get('/sitemap.xml')->assertOk();
        $response->assertHeader('Content-Type', 'application/xml; charset=UTF-8');
        $xml = $response->getContent();

        $this->assertStringContainsString('/kegiatan/panen-raya', $xml);
        $this->assertStringContainsString('/berita/berita-terbit', $xml);
        $this->assertStringNotContainsString('masih-draft', $xml);
        $this->assertStringNotContainsString('berita-terjadwal', $xml);
        $this->assertNotFalse(simplexml_load_string($xml), 'Sitemap harus XML yang valid');
    }

    public function test_layout_memuat_meta_seo(): void
    {
        $this->get(route('home'))
            ->assertSee('<link rel="canonical"', false)
            ->assertSee('property="og:title"', false)
            ->assertSee('name="twitter:card"', false)
            ->assertSee('"@context":"https://schema.org"', false)
            ->assertSee('"@type":"GovernmentOrganization"', false);
    }

    public function test_berita_memuat_structured_data_artikel(): void
    {
        Post::create(['title' => 'Uji', 'slug' => 'uji', 'body' => 'isi', 'status' => 'published', 'published_at' => now()->subMinute()]);

        $this->get(route('posts.show', 'uji'))
            ->assertOk()
            ->assertSee('"@type":"NewsArticle"', false)
            ->assertSee('property="og:type" content="article"', false);
    }

    public function test_halaman_hasil_pencarian_tidak_diindeks(): void
    {
        $this->get(route('search', ['q' => 'padi']))->assertSee('name="robots" content="noindex, follow"', false);
    }
}
