<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_berhasil_mengarahkan_ke_dashboard(): void
    {
        $user = User::factory()->create(['email' => 'petugas@bpp.test', 'role' => 'editor']);

        $this->post(route('admin.login.submit'), ['email' => 'petugas@bpp.test', 'password' => 'password'])
            ->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_login_gagal_dengan_kata_sandi_salah(): void
    {
        User::factory()->create(['email' => 'petugas@bpp.test']);

        $this->post(route('admin.login.submit'), ['email' => 'petugas@bpp.test', 'password' => 'salah'])
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_login_dikunci_setelah_5_percobaan_gagal(): void
    {
        User::factory()->create(['email' => 'petugas@bpp.test']);

        for ($i = 0; $i < 5; $i++) {
            $this->post(route('admin.login.submit'), ['email' => 'petugas@bpp.test', 'password' => 'salah']);
        }

        // Percobaan ke-6 ditolak walau kata sandinya benar.
        $this->post(route('admin.login.submit'), ['email' => 'petugas@bpp.test', 'password' => 'password'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_semua_halaman_admin_butuh_login(): void
    {
        foreach (['admin.dashboard', 'admin.activities.index', 'admin.documents.index', 'admin.posts.index',
            'admin.messages.index', 'admin.settings.edit', 'admin.gallery.index'] as $route) {
            $this->get(route($route))->assertRedirect(route('login'));
        }
    }

    public function test_editor_tidak_boleh_membuka_manajemen_pengguna_dan_log(): void
    {
        $editor = $this->editor();

        $this->actingAs($editor)->get(route('admin.users.index'))->assertForbidden();
        $this->actingAs($editor)->get(route('admin.logs.index'))->assertForbidden();
    }

    public function test_admin_boleh_membuka_manajemen_pengguna_dan_log(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->get(route('admin.users.index'))->assertOk();
        $this->actingAs($admin)->get(route('admin.logs.index'))->assertOk();
    }

    public function test_halaman_admin_tidak_boleh_diindeks_mesin_pencari(): void
    {
        $this->actingAs($this->admin())->get(route('admin.dashboard'))->assertSee('noindex, nofollow', false);
    }

    public function test_formulir_login_memuat_token_csrf(): void
    {
        $this->get(route('admin.login'))->assertSee('name="_token"', false);
    }
}
