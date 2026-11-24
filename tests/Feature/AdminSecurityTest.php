<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_penebakan_sandi_dengan_ganti_ganti_email_dikunci_per_ip(): void
    {
        for ($i = 0; $i < 20; $i++) {
            $this->post(route('admin.login.submit'), ['email' => "orang{$i}@contoh.test", 'password' => 'salah']);
        }

        User::factory()->create(['email' => 'petugas@bpp.test']);

        // Email baru yang belum pernah dicoba pun ditolak karena IP-nya sudah terkunci.
        $this->post(route('admin.login.submit'), ['email' => 'petugas@bpp.test', 'password' => 'password'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_editor_tidak_boleh_membuka_pengaturan_website(): void
    {
        $editor = $this->editor();

        $this->actingAs($editor)->get(route('admin.settings.edit'))->assertForbidden();
        $this->actingAs($editor)->put(route('admin.settings.update'), ['ppid_intro' => 'x'])->assertForbidden();
        $this->actingAs($this->admin())->get(route('admin.settings.edit'))->assertOk();
    }

    public function test_halaman_admin_tidak_disimpan_cache_dan_tidak_diindeks(): void
    {
        $response = $this->actingAs($this->admin())->get(route('admin.dashboard'));

        $this->assertStringContainsString('no-store', $response->headers->get('Cache-Control'));
        $this->assertSame('noindex, nofollow', $response->headers->get('X-Robots-Tag'));
        $this->get(route('home'))->assertHeaderMissing('X-Robots-Tag');
    }

    public function test_ganti_email_wajib_menyertakan_sandi_saat_ini(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->put(route('admin.account.update'), ['name' => $admin->name, 'email' => 'baru@bpp.test'])
            ->assertSessionHasErrors('current_password');
        $this->assertNotSame('baru@bpp.test', $admin->fresh()->email);

        $this->actingAs($admin)->put(route('admin.account.update'), [
            'name' => $admin->name, 'email' => 'baru@bpp.test', 'current_password' => 'password',
        ])->assertSessionHasNoErrors();
        $this->assertSame('baru@bpp.test', $admin->fresh()->email);
    }

    public function test_sandi_baru_harus_kuat_dan_berhasil_mengganti(): void
    {
        $admin = $this->admin();

        foreach (['pendek1A', 'sandisemuahurufkecil1', 'SANDIHURUFBESAR123'] as $lemah) {
            $this->actingAs($admin)->put(route('admin.account.update'), [
                'name' => $admin->name, 'email' => $admin->email, 'current_password' => 'password',
                'password' => $lemah, 'password_confirmation' => $lemah,
            ])->assertSessionHasErrors('password');
        }

        $this->actingAs($admin)->put(route('admin.account.update'), [
            'name' => $admin->name, 'email' => $admin->email, 'current_password' => 'password',
            'password' => 'SandiKuat2026', 'password_confirmation' => 'SandiKuat2026',
        ])->assertSessionHasNoErrors();

        $this->assertTrue(Hash::check('SandiKuat2026', $admin->fresh()->password));
    }

    public function test_admin_membuat_pengguna_dengan_sandi_lemah_ditolak(): void
    {
        $this->actingAs($this->admin())->post(route('admin.users.store'), [
            'name' => 'Petugas', 'email' => 'petugas2@bpp.test', 'role' => 'editor',
            'password' => 'abcdefgh', 'password_confirmation' => 'abcdefgh',
        ])->assertSessionHasErrors('password');
    }
}