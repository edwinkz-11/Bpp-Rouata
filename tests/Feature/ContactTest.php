<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    private function payload(array $override = []): array
    {
        return array_merge([
            'name' => 'Budi Santoso', 'email' => 'budi@example.com', 'phone' => '',
            'type' => 'pertanyaan', 'subject' => 'Jadwal penyuluhan', 'message' => 'Kapan jadwal penyuluhan padi di desa kami?',
            'website' => '',
        ], $override);
    }

    public function test_pesan_valid_tersimpan_dengan_status_baru(): void
    {
        $this->post(route('contact.store'), $this->payload())->assertRedirect(route('contact'))->assertSessionHas('sent');

        $this->assertDatabaseHas('contact_messages', ['name' => 'Budi Santoso', 'status' => 'new', 'type' => 'pertanyaan']);
    }

    public function test_bot_yang_mengisi_honeypot_ditolak(): void
    {
        $this->post(route('contact.store'), $this->payload(['website' => 'http://spam.test']))->assertSessionHasErrors('website');

        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_wajib_mengisi_email_atau_telepon(): void
    {
        $this->post(route('contact.store'), $this->payload(['email' => '', 'phone' => '']))
            ->assertSessionHasErrors(['email', 'phone']);
    }

    public function test_telepon_saja_sudah_cukup(): void
    {
        $this->post(route('contact.store'), $this->payload(['email' => '', 'phone' => '+62 812-3456-7890']))->assertSessionHasNoErrors();

        $this->assertDatabaseCount('contact_messages', 1);
    }

    public function test_validasi_pesan_terlalu_pendek_dan_jenis_tidak_dikenal(): void
    {
        $this->post(route('contact.store'), $this->payload(['message' => 'pendek', 'type' => 'iklan']))
            ->assertSessionHasErrors(['message', 'type']);
    }

    public function test_pengiriman_dibatasi_agar_tidak_jadi_spam(): void
    {
        $this->post(route('contact.store'), $this->payload())->assertRedirect();
        $this->post(route('contact.store'), $this->payload())->assertRedirect();

        $this->post(route('contact.store'), $this->payload())->assertStatus(429);
        $this->assertDatabaseCount('contact_messages', 2);
    }

    public function test_html_di_pesan_tidak_dieksekusi_di_halaman_admin(): void
    {
        $message = ContactMessage::create($this->payload(['message' => '<script>alert(1)</script> isi pesan panjang']) + ['status' => 'new']);

        $this->actingAs($this->editor())->get(route('admin.messages.show', $message))
            ->assertOk()
            ->assertDontSee('<script>alert(1)</script>', false)
            ->assertSee('&lt;script&gt;', false);
    }

    public function test_admin_membuka_pesan_otomatis_menjadi_dibaca_dan_bisa_ubah_status(): void
    {
        $message = ContactMessage::create($this->payload() + ['status' => 'new']);
        $editor = $this->editor();

        $this->actingAs($editor)->get(route('admin.messages.show', $message))->assertOk();
        $this->assertSame('read', $message->fresh()->status);

        $this->actingAs($editor)->put(route('admin.messages.update', $message), ['status' => 'resolved'])->assertRedirect();
        $this->assertSame('resolved', $message->fresh()->status);

        $this->actingAs($editor)->put(route('admin.messages.update', $message), ['status' => 'ngawur'])->assertSessionHasErrors('status');
    }

    public function test_hanya_admin_yang_boleh_menghapus_pesan(): void
    {
        $message = ContactMessage::create($this->payload() + ['status' => 'new']);

        $this->actingAs($this->editor())->delete(route('admin.messages.destroy', $message))->assertForbidden();
        $this->actingAs($this->admin())->delete(route('admin.messages.destroy', $message))->assertRedirect();
        $this->assertDatabaseCount('contact_messages', 0);
    }
}
