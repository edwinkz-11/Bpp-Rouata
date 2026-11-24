<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function test_tamu_tidak_bisa_menulis_berita(): void
    {
        $this->get(route('admin.posts.create'))->assertRedirect(route('login'));
    }

    public function test_pengelola_menerbitkan_berita_dengan_slug_unik(): void
    {
        $editor = $this->editor();
        $data = ['title' => 'Panen Raya Padi', 'body' => "Paragraf satu.\n\nParagraf dua.", 'status' => 'published'];

        $this->actingAs($editor)->post(route('admin.posts.store'), $data)->assertRedirect(route('admin.posts.index'));
        $this->actingAs($editor)->post(route('admin.posts.store'), $data)->assertRedirect();

        $this->assertSame(['panen-raya-padi', 'panen-raya-padi-2'], Post::orderBy('id')->pluck('slug')->all());
        $this->assertNotNull(Post::first()->published_at, 'Berita terbit tanpa tanggal otomatis memakai waktu sekarang');
    }

    public function test_berita_draft_dan_terjadwal_tidak_tampil_di_publik(): void
    {
        Post::create(['title' => 'Masih Draft', 'slug' => 'draft', 'body' => 'x', 'status' => 'draft']);
        Post::create(['title' => 'Akan Terbit', 'slug' => 'nanti', 'body' => 'x', 'status' => 'published', 'published_at' => now()->addDay()]);
        Post::create(['title' => 'Sudah Terbit', 'slug' => 'terbit', 'body' => 'x', 'status' => 'published', 'published_at' => now()->subHour()]);

        $this->get(route('news'))->assertSee('Sudah Terbit')->assertDontSee('Masih Draft')->assertDontSee('Akan Terbit');
        $this->get(route('posts.show', 'draft'))->assertNotFound();
        $this->get(route('posts.show', 'nanti'))->assertNotFound();
        $this->get(route('posts.show', 'terbit'))->assertOk();
    }

    public function test_html_di_isi_berita_di_escape(): void
    {
        Post::create(['title' => 'Uji XSS', 'slug' => 'xss', 'body' => '<script>alert("x")</script><b>tebal</b>', 'status' => 'published', 'published_at' => now()->subMinute()]);

        $this->get(route('posts.show', 'xss'))
            ->assertDontSee('<script>alert("x")</script>', false)
            ->assertDontSee('<b>tebal</b>', false)
            ->assertSee('&lt;script&gt;', false);
    }

    public function test_slug_tidak_berubah_setelah_terbit_walau_judul_diganti(): void
    {
        $post = Post::create(['title' => 'Judul Lama', 'slug' => 'judul-lama', 'body' => 'x', 'status' => 'published', 'published_at' => now()->subDay()]);

        $this->actingAs($this->editor())->put(route('admin.posts.update', $post), ['title' => 'Judul Baru', 'body' => 'x', 'status' => 'published'])->assertRedirect();

        $this->assertSame('judul-lama', $post->fresh()->slug);
        $this->assertSame('Judul Baru', $post->fresh()->title);
    }

    public function test_foto_sampul_dioptimalkan_dan_dihapus_bersama_berita(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin())->post(route('admin.posts.store'), [
            'title' => 'Dengan Foto', 'body' => 'isi', 'status' => 'draft',
            'cover_image' => UploadedFile::fake()->image('foto.jpg', 3000, 2000),
        ])->assertRedirect();

        $post = Post::firstOrFail();
        $this->assertStringEndsWith('.webp', $post->cover_image);
        Storage::disk('public')->assertExists($post->cover_image);

        $this->actingAs($this->admin())->delete(route('admin.posts.destroy', $post))->assertRedirect();
        Storage::disk('public')->assertMissing($post->cover_image);
    }

    public function test_validasi_judul_dan_isi_wajib(): void
    {
        $this->actingAs($this->editor())->post(route('admin.posts.store'), ['status' => 'draft'])->assertSessionHasErrors(['title', 'body']);
    }

    public function test_beranda_menampilkan_berita_bpp_terbaru(): void
    {
        $this->get(route('home'))->assertDontSee('Berita Terbaru BPP');

        Post::create(['title' => 'Kabar dari Lapangan', 'slug' => 'kabar', 'body' => 'x', 'status' => 'published', 'published_at' => now()->subMinute()]);

        $this->get(route('home'))->assertSee('Berita Terbaru BPP')->assertSee('Kabar dari Lapangan');
    }
}
