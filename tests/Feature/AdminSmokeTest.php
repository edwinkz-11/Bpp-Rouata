<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\ContactMessage;
use App\Models\Document;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminSmokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_semua_halaman_admin_dirender_tanpa_error(): void
    {
        $this->seed();
        $admin = $this->admin();
        $post = Post::create(['title' => 'Berita Uji', 'slug' => 'berita-uji', 'body' => 'isi', 'status' => 'published', 'published_at' => now()]);
        $doc = Document::create(['title' => 'Dok Uji', 'category' => 'Formulir', 'file_path' => 'dokumen/x.pdf', 'file_ext' => 'pdf', 'is_published' => true]);
        $msg = ContactMessage::create(['name' => 'Ani', 'email' => 'ani@example.com', 'type' => 'saran', 'subject' => 'Saran', 'message' => 'Isi saran yang cukup panjang.', 'status' => 'new']);
        $activity = Activity::first();

        $urls = [
            route('admin.dashboard'), route('admin.settings.edit'), route('admin.users.index'), route('admin.logs.index'),
            route('admin.documents.index'), route('admin.documents.create'), route('admin.documents.edit', $doc),
            route('admin.posts.index'), route('admin.posts.create'), route('admin.posts.edit', $post),
            route('admin.messages.index'), route('admin.messages.show', $msg),
            route('admin.activities.index'), route('admin.commodities.index'), route('admin.villages.index'),
            route('admin.locations.index'), route('admin.routes.index'), route('admin.workers.index'),
            route('admin.gallery.index'), route('admin.categories.index'), route('admin.account.edit'),
            route('admin.profile.edit', 'bpp'), route('admin.profile.edit', 'kecamatan'),
        ];
        if ($activity) {
            $urls[] = route('admin.activities.edit', $activity);
        }

        foreach ($urls as $url) {
            $this->actingAs($admin)->get($url)->assertOk();
        }
    }

    public function test_menu_menampilkan_jumlah_pesan_belum_dibaca(): void
    {
        ContactMessage::create(['name' => 'Ani', 'email' => 'ani@example.com', 'type' => 'saran', 'subject' => 'S', 'message' => 'Isi saran yang cukup panjang.', 'status' => 'new']);

        $this->actingAs($this->editor())->get(route('admin.dashboard'))->assertSee('Pesan Masuk')->assertSee('text-bg-warning');
    }

    public function test_pengaturan_ppid_tersimpan_dan_tampil_di_halaman_ppid(): void
    {
        $this->actingAs($this->admin())->put(route('admin.settings.update'), ['ppid_intro' => 'Permohonan informasi dilayani Senin sampai Jumat.'])
            ->assertRedirect(route('admin.settings.edit'));

        $this->get(route('ppid'))->assertSee('Permohonan informasi dilayani Senin sampai Jumat.');
    }

    public function test_halaman_publik_dengan_data_contoh_dirender(): void
    {
        $this->seed();

        foreach (['home', 'district', 'activities.index', 'commodities.index', 'news', 'gallery', 'about'] as $route) {
            $this->get(route($route))->assertOk();
        }
        foreach (Activity::published()->take(3)->get() as $a) {
            $this->get(route('activities.show', $a->slug))->assertOk();
        }
    }
}
