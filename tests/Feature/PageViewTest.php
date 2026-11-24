<?php

namespace Tests\Feature;

use App\Models\PageView;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageViewTest extends TestCase
{
    use RefreshDatabase;

    public function test_kunjungan_halaman_publik_dihitung_per_hari(): void
    {
        $this->get(route('about'));
        $this->get(route('about'));
        $this->get(route('home'));

        $this->assertSame(2, PageView::where('path', '/tentang-bpp')->value('views'));
        $this->assertSame(1, PageView::where('path', '/')->value('views'));
        $this->assertSame(3, PageView::summary()['total']);
    }

    public function test_bot_admin_dan_halaman_teknis_tidak_dihitung(): void
    {
        $this->withHeader('User-Agent', 'Googlebot/2.1')->get(route('about'));
        $this->get(route('admin.login'));
        $this->get('/sitemap.xml');
        $this->get('/robots.txt');
        $this->get('/halaman-404');

        $this->assertDatabaseCount('page_views', 0);
    }

    public function test_tidak_menyimpan_ip_atau_data_pribadi(): void
    {
        $this->get(route('about'));

        $this->assertSame(['id', 'date', 'path', 'views'], array_keys(PageView::first()->getAttributes()));
    }

    public function test_dashboard_menampilkan_statistik(): void
    {
        $this->get(route('about'));

        $this->actingAs($this->admin())->get(route('admin.dashboard'))->assertOk()->assertSee('Statistik Kunjungan')->assertSee('/tentang-bpp');
    }
}
