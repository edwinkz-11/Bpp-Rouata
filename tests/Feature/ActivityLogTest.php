<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\ActivityLog;
use App\Models\Commodity;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityLogTest extends TestCase
{
    use RefreshDatabase;

    public function test_tambah_ubah_hapus_oleh_pengelola_tercatat(): void
    {
        $admin = $this->admin();
        $this->actingAs($admin);

        $c = Commodity::create(['name' => 'Padi Sawah', 'category' => 'Tanaman Pangan', 'status' => 'active']);
        $c->update(['description' => 'Deskripsi baru']);
        $c->delete();

        $logs = ActivityLog::orderBy('id')->get();
        $this->assertSame(['created', 'updated', 'deleted'], $logs->pluck('action')->all());
        $this->assertSame($admin->id, $logs[0]->user_id);
        $this->assertSame($admin->name, $logs[0]->user_name);
        $this->assertSame('Commodity', $logs[0]->subject_type);
        $this->assertSame('Padi Sawah', $logs[0]->subject_label);
        $this->assertSame('description', $logs[1]->changes);
    }

    public function test_hanya_nama_kolom_yang_dicatat_bukan_isi_termasuk_kata_sandi(): void
    {
        $this->actingAs($this->admin());
        $user = User::factory()->create();

        $user->update(['password' => 'RahasiaBaru12345']);

        $log = ActivityLog::where('action', 'updated')->latest('id')->first();
        $this->assertSame('password', $log->changes);
        $this->assertStringNotContainsString('RahasiaBaru12345', json_encode($log->toArray()));
    }

    public function test_tidak_mencatat_jika_tidak_ada_pengguna_login(): void
    {
        Activity::create(['title' => 'Lewat seeder', 'slug' => 'lewat-seeder', 'status' => 'draft']);

        $this->assertDatabaseCount('activity_logs', 0);
    }

    public function test_nama_petugas_tetap_ada_walau_akunnya_dihapus(): void
    {
        $editor = $this->editor();
        $this->actingAs($editor);
        Commodity::create(['name' => 'Jagung', 'category' => 'Tanaman Pangan', 'status' => 'active']);

        $this->actingAs($this->admin());
        $editor->delete();

        $this->assertSame($editor->name, ActivityLog::where('subject_type', 'Commodity')->first()->user_name);
    }

    public function test_halaman_log_menampilkan_dan_menyaring_catatan(): void
    {
        $admin = $this->admin();
        $this->actingAs($admin);
        Commodity::create(['name' => 'Cabai Rawit', 'category' => 'Hortikultura', 'status' => 'active']);

        $this->get(route('admin.logs.index'))->assertSee('Cabai Rawit')->assertSee('Menambah');
        $this->get(route('admin.logs.index', ['action' => 'deleted']))->assertDontSee('Cabai Rawit');
    }

    public function test_simpan_lewat_panel_admin_tercatat_dengan_ip(): void
    {
        $editor = $this->editor();

        $this->actingAs($editor)->post(route('admin.posts.store'), ['title' => 'Berita Log', 'body' => 'isi', 'status' => 'draft']);

        $log = ActivityLog::where('subject_type', 'Post')->first();
        $this->assertNotNull($log);
        $this->assertSame('127.0.0.1', $log->ip_address);
    }
}
