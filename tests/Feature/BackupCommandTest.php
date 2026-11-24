<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\File;
use Tests\TestCase;

class BackupCommandTest extends TestCase
{
    private string $dir;

    protected function setUp(): void
    {
        parent::setUp();
        $this->dir = storage_path('app/backups');
        File::deleteDirectory($this->dir);
    }

    protected function tearDown(): void
    {
        File::deleteDirectory($this->dir);
        parent::tearDown();
    }

    public function test_backup_sqlite_membuat_file(): void
    {
        $this->artisan('backup:database')->assertSuccessful();

        $this->assertCount(1, File::files($this->dir));
    }

    public function test_backup_lama_dipangkas_sesuai_opsi_keep(): void
    {
        File::ensureDirectoryExists($this->dir);
        foreach (['20260101-000000', '20260102-000000', '20260103-000000'] as $stamp) {
            File::put("{$this->dir}/db-{$stamp}.sqlite", 'lama');
        }

        $this->artisan('backup:database', ['--keep' => 2])->assertSuccessful();

        $names = collect(File::files($this->dir))->map->getFilename()->sort()->values();
        $this->assertCount(2, $names);
        $this->assertNotContains('db-20260101-000000.sqlite', $names->all());
        $this->assertNotContains('db-20260102-000000.sqlite', $names->all());
    }

    public function test_backup_terjadwal_harian(): void
    {
        $events = collect(app(\Illuminate\Console\Scheduling\Schedule::class)->events())
            ->filter(fn ($e) => str_contains($e->command, 'backup:database'));

        $this->assertCount(1, $events);
        $this->assertSame('0 2 * * *', $events->first()->expression);
    }
}
